<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReestrOrg;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Validator;
use Illuminate\Support\Facades\DB;

class ReestrOrgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reestr = ReestrOrg::orderBy('id', 'desc')->paginate(10);

        return view('admin.pages.reestr_org', ['reestr' => $reestr]);
    }

    public function all(Request $request)
    {
        // массив для поиска
        $array_search = [];

        // данные запроса поиска
        $snum = $request->snum_cert;
        $sname = '%'.$request->sname_org.'%';
        $scity = '%'.$request->scity.'%';
        $sregion = '%'.$request->sregion.'%';
        // $sstage_from = $request->sstage_from;
        // $sstage_to = $request->sstage_to;

        if(!empty($request->sdate_start)) $sdate_start = date("Y-m-d", strtotime($request->sdate_start));
        if(!empty($request->sdate_end)) $sdate_end = date("Y-m-d", strtotime($request->sdate_end));

        // сортировка по стажу, формула: текущая дата минус заданное число (год) стажа
        // $stage_form_val = strtotime("-".$sstage_from." year");
        // $stage_date_from = date("Y-m-d", $stage_form_val);

        // здесь нужно прибавить один год, т.к. стаж при выводе округляется до целого числа в меньшую сторону
        // $sstage_to = (int)$sstage_to + 1;

        // $stage_to_val = strtotime("-".$sstage_to." year");
        // $stage_date_to = date("Y-m-d", $stage_to_val);

        // формирование запросов для поиска по базе
        if($request->snum_cert) array_push($array_search, ['num_cert', '=', $snum ]);
        if($request->sname_org) array_push($array_search, ['name_org', 'like', $sname]);
        if($request->scity) array_push($array_search, ['city', 'like', $scity]);
        if($request->sregion) array_push($array_search, ['region', 'like', $sregion]);
        if($request->sdate_start) array_push($array_search, ['date_start', '>=', $sdate_start]);
        if($request->sdate_end) array_push($array_search, ['date_end', '<=', $sdate_end]);

        // количество записей на странице
        $count = $request->query('count');
        $paginate = 20;
        if($count) $paginate = $count;

        // строка запроса
        $route_path = $request->except('count');

        if(!empty($array_search)){
            $reestr = DB::table('reestr_orgs')
                    ->where($array_search)
                    ->orderBy('id')
                    ->paginate($paginate)
                    ->withQueryString();

            return view('pages.reestr_org', ['reestr' => $reestr, 'count' => $count, 'reestr_search' => $request, 'route_path' => $route_path]);
        }

        $reestr = ReestrOrg::orderBy('id')->paginate($paginate)->withQueryString();

        return view('pages.reestr_org', ['reestr' => $reestr, 'count' => $count, 'route_path' => $route_path]);
    }

    public function indexUpload()
    {
        return view('admin.pages.reestr_org_load');
    }

    public function uploadReestr(Request $request)
    {
        $inputFileType = 'Xlsx';

        // $inputFileName = './docs/reestr.xlsx';

        $inputFileName = $request->file('file');

        $reader = IOFactory::createReader($inputFileType);

        $reader->setReadEmptyCells(false);

        $spreadsheet = $reader->load($inputFileName);

        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $sheetDataShift = array_shift($sheetData);

        // dd($sheetData);

        if(isset($request->check)){
            ReestrOrg::truncate();
        }

        $rowNumber = 2;

        foreach($sheetData as $data){
            if(!empty($data['D'])){
                // $date_start = date("Y-m-d", strtotime($data['G']));
                // $date_end = date("Y-m-d", strtotime($data['H']));
                $cellG = $spreadsheet->getActiveSheet()->getCell('G' . $rowNumber);
                $cellH = $spreadsheet->getActiveSheet()->getCell('H' . $rowNumber);
                if (Date::isDateTime($cellG)) {
                    $date_start = Date::excelToDateTimeObject($cellG->getValue())->format('Y-m-d');
                } else {
                    $date_start = null;
                }
                if (Date::isDateTime($cellH)) {
                    $date_end = Date::excelToDateTimeObject($cellH->getValue())->format('Y-m-d');
                } else {
                    $date_end = null;
                }

                // $num_cert = str_pad($data['C'], 2, '0', STR_PAD_LEFT);

                if(isset($data['J'])) $subdiv = $data['J']; else $subdiv = NULL;
                if(isset($data['K'])) $manager = $data['K']; else $manager = NULL;
                if(isset($data['L'])) $website = $data['L']; else $website = NULL;
                if(isset($data['M'])) $phone = $data['M']; else $phone = NULL;
                // if(isset($data['N'])) $ur_address = $data['N']; else $ur_address = NULL;
                if(isset($data['N'])) $address = $data['N']; else $address = NULL;
                if(isset($data['O'])) $email = $data['O']; else $email = NULL;
                if(isset($data['P'])) $boss = $data['P']; else $boss = NULL;
                if(isset($data['I'])) $program = $data['I']; else $program = NULL;

                // if(isset($data['H'])){
                //     $program = explode(";", $data['H']);
                // }else{
                //     $program = NULL;
                // }

                $reestr = ReestrOrg::create([
                    'name_org' => $data['A'],
                    'name_org_full' => $data['B'],
                    'name_org_short' => $data['C'],
                    'subdiv' => $subdiv,
                    'num_cert' => $data['D'],
                    'city' => $data['E'],
                    'region' => $data['F'],
                    'date_start' => $date_start,
                    'date_end' => $date_end,
                    'manager' => $manager,
                    'website' => $website,
                    'phone' => $phone,
                    // 'ur_address' => $ur_address,
                    'address' => $address,
                    'email' => $email,
                    'boss' => $boss,
                    'program' => $program
                ]);
            }

            $rowNumber++;
        }

        return redirect()->route('admin.reestr_org_load')->with(['status' => true]);
    }

    public function org($id)
    {
        $org = ReestrOrg::where('num_cert', $id)->first();

        $curr_date = date("d.m.Y");
        $expire = false;
        if(strtotime($curr_date) > strtotime($org->date_end) && $org->date_end !== null){
            $expire = true;
        }

        $program = explode(";", $org->program);

        return view('pages.inner.org', ['org' => $org, 'program' => $program, 'expire' => $expire]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.reestr_org_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "num_cert" => ["required"],
                "name_org" => ["required"]
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with(["status" => false, "errors" => $validator->messages()]);
        }

        $date_start = date("Y-m-d", strtotime($request->date_start));
        $date_end = date("Y-m-d", strtotime($request->date_end));

        // $program = NULL;

        // if(isset($request->program)){
        //     // foreach($request->program as $prog){
        //     //     $program .= '[' . $prog . '],';
        //     // }
        //     $program = json_encode($request->program);
        // }

        $program = json_encode($request->program);

        $reestr = ReestrOrg::create([
            "num_cert" => $request->num_cert,
            "name_org" => $request->name_org,
            "subdiv" => $request->subdiv,
            "city" => $request->city,
            "region" => $request->region,
            "date_start" => $date_start,
            "date_end" => $date_end,
            "manager" => $request->manager,
            "website" => $request->website,
            "phone" => $request->phone,
            "ur_address" => $request->ur_address,
            "address" => $request->address,
            "email" => $request->email,
            "boss" => $request->boss,
            "program" => $program
        ]);

        return redirect()->route('reestr_org.edit', $reestr->id)->with(['status' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reestr = ReestrOrg::find($id);

        return view('admin.pages.reestr_org_edit', ['reestr' => $reestr]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "num_cert" => ["required"],
                "name_org" => ["required"]
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with(["status" => false, "errors" => $validator->messages()]);
        }

        $reestr = ReestrOrg::find($id);

        $date_start = date("Y-m-d", strtotime($request->date_start));
        $date_end = date("Y-m-d", strtotime($request->date_end));

        $reestr->num_cert = $request->num_cert;
        $reestr->name_org = $request->name_org;
        $reestr->subdiv = $request->subdiv;
        $reestr->city = $request->city;
        $reestr->region = $request->region;
        $reestr->date_start = $date_start;
        $reestr->date_end = $date_end;
        $reestr->manager = $request->manager;
        $reestr->website = $request->website;
        $reestr->phone = $request->phone;
        $reestr->ur_address = $request->ur_address;
        $reestr->address = $request->address;
        $reestr->email = $request->email;
        $reestr->boss = $request->boss;
        $reestr->program = json_encode($request->program);

        if($reestr->isDirty()){
            $reestr->save();
        }

        return redirect()->back()->with(['status' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reestr = ReestrOrg::find($id);
        if($reestr) {
            $reestr->delete();
            return redirect()->route('admin.reestr_org')->with(["status" => true]);
        }else{
            return redirect()->route('admin.reestr_org')->with(["status" => false]);
        }
    }
}
