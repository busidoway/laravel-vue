<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\OrganizationCityJoin;
use App\Models\City;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
// use Intervention\Image\ImageManager;
// use Intervention\Image\Drivers\Imagick\Driver;

class OrganizationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Organization::all();
    }

    public function all(Request $request)
    {
        // массив для поиска
        $array_search = [];

        // данные запроса поиска
        $snum = $request->snum_cert;
        $sname = '%'.$request->sname_org.'%';
        $scity = $request->scity;
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
        if($request->sname_org) array_push($array_search, ['organizations.name', 'like', $sname]);
        if($request->scity) array_push($array_search, ['city_id', '=', $scity]);
        // if($request->sregion) array_push($array_search, ['region', 'like', $sregion]);
        if($request->sdate_start) array_push($array_search, ['date_start', '>=', $sdate_start]);
        if($request->sdate_end) array_push($array_search, ['date_end', '<=', $sdate_end]);

        // количество записей на странице
        $count = $request->query('count');
        $paginate = 20;
        if($count) $paginate = $count;

        // строка запроса
        $route_path = $request->except('count');

        $cities = City::all();

        if(!empty($array_search)){
            $reestr = DB::table('organizations')
                ->select('organizations.*', 'cities.name as city_name', 'cities.id as city_id')
                ->join('organization_city_joins', 'organizations.id', '=', 'organization_city_joins.organization_id')
                ->join('cities', 'cities.id', '=', 'organization_city_joins.city_id')
                ->where($array_search)
                ->where('hidden_reestr', NULL)
                ->orderBy('organizations.id')
                ->paginate($paginate)
                ->withQueryString();

            return view('pages.reestr_org', ['reestr' => $reestr, 'count' => $count, 'reestr_search' => $request, 'cities' => $cities, 'route_path' => $route_path]);
        }

        // $reestr = Organization::orderBy('id')->paginate($paginate)->withQueryString();

        $reestr = DB::table('organizations')
            ->select('organizations.*', 'cities.name as city_name')
            ->join('organization_city_joins', 'organizations.id', '=', 'organization_city_joins.organization_id')
            ->join('cities', 'cities.id', '=', 'organization_city_joins.city_id')
            ->where('hidden_reestr', NULL)
            ->orderBy('organizations.id')
            ->paginate($paginate)
            ->withQueryString();

        return view('pages.reestr_org', ['reestr' => $reestr, 'count' => $count, 'route_path' => $route_path, 'cities' => $cities]);
    }

    public function getOrgFilter($cat)
    {
        $orgs = DB::table('organizations')
            ->select('organizations.id', 'organizations.name', 'organizations.name_short', 'organizations.name_full', 'organizations.name_filter', DB::raw('COUNT(organizations.id) as organizations_count'))
            ->join('programs_education', 'organizations.id', '=', 'programs_education.organization_id')
            ->join('program_type_program_joins', 'programs_education.program_id', '=', 'program_type_program_joins.program_id')
            ->where('program_type_program_joins.type_program_id', $cat)
            ->groupBy('organizations.id')
            ->get();

        return $orgs;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Валидация входящих данных
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_short' => 'required|string|max:255',
            'name_filter' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'city' => 'required|exists:cities,id',
            'dataFiles.*' => 'file|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        $hidden_program = NULL;
        if(isset($request->hidden_program) && ($request->hidden_program == 1 || $request->hidden_program == '1')) $hidden_program = 1;

        $hidden_reestr = NULL;
        if(isset($request->hidden_reestr) && ($request->hidden_reestr == 1 || $request->hidden_reestr == '1')) $hidden_reestr = 1;

        $hidden_more = NULL;
        if(isset($request->hidden_more) && ($request->hidden_more == 1 || $request->hidden_more == '1')) $hidden_more = 1;

        $organization = Organization::create([
            'name' => $validated['name'],
            'name_short' => $validated['name_short'],
            'name_full' => $request->name_full,
            'name_filter' => $validated['name_filter'],
            'email' => $request->email,
            'description' => $request->description,
            'subdiv' => $request->subdiv,
            'num_cert' => $request->num_cert,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'manager' => $request->manager,
            'website' => $request->website,
            'phone' => $request->phone,
            'address' => $request->address,
            'boss' => $request->boss,
            'program' => $request->program,
            'hidden_program' => $hidden_program,
            'hidden_reestr' => $hidden_reestr,
            'hidden_more' => $hidden_more
        ]);

        if ($request->hasFile('dataFiles')) {
            foreach ($request->file('dataFiles') as $file) {
                // Сохранение файла в директорию 'images/logo'
                $filePath = $file->store('images/logo', 'public');
                // Сохранение пути к файлу в поле 'logo' (в этом примере сохраняем только первый файл)
                $organization->logo = '/' . $filePath;
                $organization->save();
            }
        }

        // Настройка ImageManager с использованием Imagick
        // $manager = new ImageManager(['driver' => 'imagick']);
        //
        // // Обработка файлов
        // if ($request->hasFile('dataFiles')) {
        //     foreach ($request->file('dataFiles') as $file) {
        //         // Создание изображения с помощью ImageManager
        //         $image = $manager->make($file);
        //
        //         // Изменение размера изображения по высоте
        //         $image->heighten(300, function ($constraint) {
        //             $constraint->aspectRatio();
        //             $constraint->upsize();
        //         });
        //
        //         // Сохранение изображения в хранилище
        //         $filePath = 'images/logo/' . uniqid() . '.' . $file->getClientOriginalExtension();
        //         Storage::disk('public')->put($filePath, (string) $image->encode());
        //
        //         // Сохранение пути к изображению в базе данных
        //         $organization->logo = $filePath;
        //         $organization->save();
        //     }
        // }

        $org_city = OrganizationCityJoin::create([
            'organization_id' => $organization->id,
            'city_id' => $validated['city']
        ]);

        return $organization;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $org = Organization::find($id);

        if(!$org->hidden_more) {

            $curr_date = date("d.m.Y");
            $expire = false;
            if (strtotime($curr_date) > strtotime($org->date_end) && $org->date_end !== null) {
                $expire = true;
            }

            $org_city = OrganizationCityJoin::where('organization_id', $org->id)->first();
            $city = City::find($org_city->city_id);

            $program = explode(";", $org->program);

            return view('pages.inner.org', ['org' => $org, 'city' => $city, 'program' => $program, 'expire' => $expire]);

        } else {
            return view('pages.inner.org', ['org' => NULL]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $org = Organization::find($id);
        $org_city = OrganizationCityJoin::where('organization_id', $org->id)->first();

        return ['org' => $org, 'city' => $org_city];
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
        // dd($id);
        if($id){
            // Валидация входящих данных
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'name_short' => 'required|string|max:255',
                'name_filter' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'city' => 'required|exists:cities,id',
                'dataFiles.*' => 'file|mimes:jpg,jpeg,png,gif|max:2048'
            ]);

            $hidden_program = NULL;
            if(isset($request->hidden_program) && ($request->hidden_program == 1 || $request->hidden_program == '1')) $hidden_program = 1;

            $hidden_reestr = NULL;
            if(isset($request->hidden_reestr) && ($request->hidden_reestr == 1 || $request->hidden_reestr == '1')) $hidden_reestr = 1;

            $hidden_more = NULL;
            if(isset($request->hidden_more) && ($request->hidden_more == 1 || $request->hidden_more == '1')) $hidden_more = 1;

            $organizations = Organization::find($id);
            $organizations->name = $validated['name'];
            $organizations->name_short = $validated['name_short'];
            $organizations->name_full = $request->name_full;
            $organizations->name_filter = $validated['name_filter'];
            $organizations->email = $validated['email'];
            $organizations->description = $request->description;
            $organizations->subdiv = $request->subdiv;
            $organizations->num_cert = $request->num_cert;
            $organizations->date_start = $request->date_start;
            $organizations->date_end = $request->date_end;
            $organizations->manager = $request->manager;
            $organizations->website = $request->website;
            $organizations->phone = $request->phone;
            $organizations->address = $request->address;
            $organizations->boss = $request->boss;
            $organizations->program = $request->program;
            $organizations->hidden_program = $hidden_program;
            $organizations->hidden_reestr = $hidden_reestr;
            $organizations->hidden_more = $hidden_more;

            if ($request->hasFile('dataFiles')) {
                foreach ($request->file('dataFiles') as $file) {
                    // Сохранение файла в директорию 'images/logo'
                    $filePath = $file->store('images/logo', 'public');
                    // Сохранение пути к файлу в поле 'logo' (в этом примере сохраняем только первый файл)
                    $organizations->logo = '/' . $filePath;
                    // $organizations->save();
                }
            }

            $org_city = OrganizationCityJoin::where('organization_id', $organizations->id)->first();

            if($org_city){
                $org_city->city_id = $validated['city'];
                if($org_city->isDirty()){
                    $org_city->save();
                }
            }else{
                $org_city_new = OrganizationCityJoin::create([
                    'organization_id' => $organizations->id,
                    'city_id' => $validated['city']
                ]);
            }

            if($organizations->isDirty()){
                $organizations->save();
            }

            return $organizations;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $organizations = Organization::find($id);
        if($organizations) {
            $organizations->delete();
            return ["status" => true];
        }else{
            return ["status" => false];
        }
    }
}
