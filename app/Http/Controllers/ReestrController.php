<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reestr;
use App\Models\PaymentReestr;
use App\Models\PaymentYear;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Jobs\ReestrSenderEmail;

class ReestrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        date_default_timezone_set("Europe/Moscow");
        $curr_date = date('Y-m-d');
        $date_pay = date('Y-m-d', strtotime($curr_date.' +2 month'));

        $reestr = DB::table('reestrs')
                    ->select(DB::raw("id, num_doc, name, city, region, email,
                        DATE_FORMAT(date_start, '%d.%m.%Y') as date_start, date_start as date_start_init,
                        DATE_FORMAT(date_end, '%d.%m.%Y') as date_end, date_end as date_end_init,
                        IF(CURDATE() > date_end, true, false) as expired,
                        membership"))
                    ->orderBy('id', 'desc')
                    ->get();

        $new_data = [];

        foreach ($reestr as $r) {
            $payment_reestr = PaymentReestr::select('name', 'year', 'status')->where('reestr_id', $r->id)->get();
            $coming = 0;
            if($r->date_end_init <= $date_pay) $coming = 1;
            $new_data[] = [
                'id' => $r->id,
                'num_doc' => $r->num_doc,
                'name' => $r->name,
                'email' => $r->email,
                'city' => $r->city,
                'region' => $r->region,
                'date_start' => $r->date_start,
                'date_end' => $r->date_end,
                'date_start_init' => $r->date_start_init,
                'date_end_init' => $r->date_end_init,
                'expired' => $r->expired,
                'date_coming' => $coming,
                'membership' => $r->membership,
                'payment_reestr' => $payment_reestr
            ];
        }

        return ['result' => $new_data];
    }

    public function all(Request $request)
    {
        // массив для поиска
        $array_search = [];

        // данные запроса поиска
        $snum = $request->snum;
        $sname = '%'.$request->sname.'%';
        $scity = '%'.$request->scity.'%';
        $sregion = '%'.$request->sregion.'%';
        $sstage_from = $request->sstage_from;
        $sstage_to = $request->sstage_to;

        if(!empty($request->sdate_start)) $sdate_start = date("Y-m-d", strtotime($request->sdate_start));
        if(!empty($request->sdate_end)) $sdate_end = date("Y-m-d", strtotime($request->sdate_end));

        // сортировка по стажу, формула: текущая дата минус заданное число (год) стажа
        $stage_form_val = strtotime("-".$sstage_from." year");
        $stage_date_from = date("Y-m-d", $stage_form_val);

        // здесь нужно прибавить один год, т.к. стаж при выводе округляется до целого числа в меньшую сторону
        $sstage_to = (int)$sstage_to + 1;

        $stage_to_val = strtotime("-".$sstage_to." year");
        $stage_date_to = date("Y-m-d", $stage_to_val);

        // формирование запросов для поиска по базе
        if($request->snum) array_push($array_search, ['num_doc', '=', $snum ]);
        if($request->sname) array_push($array_search, ['name', 'like', $sname]);
        if($request->scity) array_push($array_search, ['city', 'like', $scity]);
        if($request->sregion) array_push($array_search, ['region', 'like', $sregion]);
        if($request->sdate_start) array_push($array_search, ['date_start', '>=', $sdate_start]);
        if($request->sdate_end) array_push($array_search, ['date_end', '<=', $sdate_end]);
        if($request->sstage_from) array_push($array_search, ['date_doc', '<=', $stage_date_from]);
        if($request->sstage_to) array_push($array_search, ['date_doc', '>=', $stage_date_to]);

        // количество записей на странице
        $count = $request->query('count');
        $paginate = 20;
        if($count) $paginate = $count;

        // строка запроса
        $route_path = $request->except('count');

        if(!empty($array_search)){
            $reestr = DB::table('reestrs')
                    ->where($array_search)
                    ->orderBy('id')
                    ->paginate($paginate)
                    ->withQueryString();

            return view('pages.reestr', ['reestr' => $reestr, 'count' => $count, 'reestr_search' => $request, 'route_path' => $route_path]);
        }

        $reestr = Reestr::orderBy('id')->paginate($paginate)->withQueryString();

        return view('pages.reestr', ['reestr' => $reestr, 'count' => $count, 'route_path' => $route_path]);
    }

    public function person($id)
    {
        $person = Reestr::where('num_doc', $id)->first();

        $curr_date = date("d.m.Y");
        $expire = false;
        if(strtotime($curr_date) > strtotime($person->date_end)){
            $expire = true;
        }

        $stage = getStage($person->date_doc);

        return view('pages.inner.person', ['person' => $person, 'expire' => $expire, 'stage' => $stage]);
    }

    public function redir(Request $request)
    {
        if(isset($request->person)){
            $person = $request->person;
            return redirect()->route('person', ["id" => $person]);
        }else{
            return abort(404);
        }
    }

    public function indexUpload()
    {
        return view('admin.pages.reestr_load');
    }

    public function uploadReestr(Request $request)
    {
        $inputFileType = 'Xlsx';

        $inputFileName = $request->file('file');

        $reader = IOFactory::createReader($inputFileType);

        $reader->setReadEmptyCells(false);

        $spreadsheet = $reader->load($inputFileName);

        $sheetData = $spreadsheet->getActiveSheet()->toArray("", true, true, true);

        $sheetDataShift = array_shift($sheetData);

        if(isset($request->check)){
            PaymentReestr::truncate();
            Reestr::truncate();
        }

        $url = '';

        $upload_rows = [];
        $invalid_rows = [];

        foreach($sheetData as $key=>$data){
            if(!empty($data['A']) && !empty($data['B'])){

                // Валидация входящих данных
                $validated = Validator::make($data, [
                    'S' => [
                        'required',
                        'email',
                        function ($attribute, $value, $fail) {
                            if (str_contains($value, ' ')) {
                                $fail('Адрес электронной почты содержит недопустимые пробелы.');
                            }
                        },
                    ]
                ]);

                if ($validated->fails()) {
                    $invalid_rows[] = [
                        'row' => $key + 2,
                        'email' => $data['S'] ?? null,
                        'errors' => $validated->errors()->all()
                    ];
                    continue;
                } else {
                    $upload_rows[] = [
                        'row' => $key + 1
                    ];
                }

                $date_start = date("Y-m-d", strtotime($data['F']));
                $date_end = date("Y-m-d", strtotime($data['G']));
                $date_doc = date("Y-m-d", strtotime($data['I']));

                $hidden = $data['J'] ?? null;
                $membership = $data['T'] ? null : 1;

                $reestr = Reestr::create([
                    'num_doc' => (int)$data['A'],
                    'name' => trim($data['B']),
                    'city' => trim($data['C']),
                    'region' => trim($data['D']),
                    'email' => trim($data['S']),
                    'date_start' => $date_end,
                    'date_end' => $date_start,
                    'date_doc' => $date_doc,
                    // 'organization' => $data['H'],
                    'url' => $url.$data['A'],
                    'url_value' => $data['A'],
                    'hidden' => (int)$hidden,
                    'membership' => $membership
                ]);

                $name_pay_reestr = 'reestr';
                $name_pay_member = 'membership';

                if(!empty($data['K']) || $data['K'] === '0'){
                    $payment_year_2022 = PaymentReestr::create([
                        'reestr_id' => $reestr->id,
                        'name' => $name_pay_member,
                        'year' => 2022,
                        'status' => $data['K']
                    ]);
                }

                if(!empty($data['L']) || $data['L'] === '0'){
                    $payment_year_2022 = PaymentReestr::create([
                        'reestr_id' => $reestr->id,
                        'name' => $name_pay_reestr,
                        'year' => 2022,
                        'status' => $data['L']
                    ]);
                }

                if(!empty($data['M']) || $data['M'] === '0'){
                    $payment_reestr_2023 = PaymentReestr::create([
                        'reestr_id' => $reestr->id,
                        'name' => $name_pay_member,
                        'year' => 2023,
                        'status' => $data['M']
                    ]);
                }

                if(!empty($data['N']) || $data['N'] === '0'){
                    $payment_reestr_2023 = PaymentReestr::create([
                        'reestr_id' => $reestr->id,
                        'name' => $name_pay_reestr,
                        'year' => 2023,
                        'status' => $data['N']
                    ]);
                }

                if(!empty($data['O']) || $data['O'] === '0'){
                    $payment_reestr_2024 = PaymentReestr::create([
                        'reestr_id' => $reestr->id,
                        'name' => $name_pay_member,
                        'year' => 2024,
                        'status' => $data['O']
                    ]);
                }

                if(!empty($data['P']) || $data['P'] === '0'){
                    $payment_reestr_2024 = PaymentReestr::create([
                        'reestr_id' => $reestr->id,
                        'name' => $name_pay_reestr,
                        'year' => 2024,
                        'status' => $data['P']
                    ]);
                }

                if(!empty($data['Q']) || $data['Q'] === '0'){
                    $payment_reestr_2025 = PaymentReestr::create([
                        'reestr_id' => $reestr->id,
                        'name' => $name_pay_member,
                        'year' => 2025,
                        'status' => $data['Q']
                    ]);
                }

                if(!empty($data['R']) || $data['R'] === '0'){
                    $payment_reestr_2025 = PaymentReestr::create([
                        'reestr_id' => $reestr->id,
                        'name' => $name_pay_reestr,
                        'year' => 2025,
                        'status' => $data['R']
                    ]);
                }
            }
        }

        $upload_rows_count = count($upload_rows);
        $invalid_rows_count = count($invalid_rows);

        return redirect()->route('admin.reestr_load')->with([
            'status' => true,
            'invalid_rows' => $invalid_rows,
            'upload_rows_count' => $upload_rows_count,
            'invalid_rows_count' => $invalid_rows_count
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.reestr_create');
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
                "num_doc" => ["required"],
                "name" => ["required"]
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with(["status" => false, "errors" => $validator->messages()]);
        }

        $date_start = date("Y-m-d", strtotime($request->date_start));
        $date_end = date("Y-m-d", strtotime($request->date_end));
        $date_doc = date("Y-m-d", strtotime($request->date_doc));

        $url = '';

        $reestr = Reestr::create([
            "num_doc" => $request->num_doc,
            "name" => $request->name,
            "city" => $request->city,
            "region" => $request->region,
            "date_start" => $date_start,
            "date_end" => $date_end,
            "date_doc" => $date_doc,
            "organization" => $request->organization,
            "url" => $url.$request->num_doc,
            "url_value" => $request->num_doc
        ]);

        return redirect()->route('reestr.edit', $reestr->id)->with(['status' => true]);
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
        $reestr = Reestr::find($id);

        return view('admin.pages.reestr_edit', ['reestr' => $reestr]);
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
                "num_doc" => ["required"],
                "name" => ["required"]
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with(["status" => false, "errors" => $validator->messages()]);
        }

        $reestr = Reestr::find($id);

        $date_start = date("Y-m-d", strtotime($request->date_start));
        $date_end = date("Y-m-d", strtotime($request->date_end));
        $date_doc = date("Y-m-d", strtotime($request->date_doc));

        $url = '';

        $reestr->num_doc = $request->num_doc;
        $reestr->name = $request->name;
        $reestr->city = $request->city;
        $reestr->region = $request->region;
        $reestr->date_start = $date_start;
        $reestr->date_end = $date_end;
        $reestr->date_doc = $date_doc;
        $reestr->organization = $request->organization;
        $reestr->url = $url.$request->num_doc;
        $reestr->url_value = $request->num_doc;

        if($reestr->isDirty()){
            $reestr->save();
        }

        return redirect()->back()->with(['status' => true]);
    }

    public function checkPayment(Request $request)
    {

        $response = array();

        $response['status'] = '';

        $response['error'] = 0;

        $html_template = '';

        $validator = Validator::make(
            $request->all(),
            [
                "last_name" => ["required"],
                "name" => ["required"],
                "surname" => ["required"]
            ]
        );

        if ($validator->fails()) {

            $response['error'] = 1;
            $response['status'] = 'validate';
            $response['last_name'] = $request->last_name;
            $response['name'] = $request->name;
            $response['surname'] = $request->surname;

            return response()->json($response, 200);

            // return view('pages.check_payment', ['response' => $response]);
        }

        // if(!empty($request->name)) {

            $name = trim($_POST['last_name']) . ' ' . trim($_POST['name']) . ' ' . trim($_POST['surname']);

			$host = '';
			$domain = '';
			$secure = 'ssl';
			$mailer = 'smtp';
			$port = 465;

            $username = '';
            $password = '';
            $mail_from = "";
            $mail_to = "";

            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';

            // Настройки SMTP
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPDebug = 0;

            $mail->Host = $host;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->SMTPSecure = $secure;
            $mail->Port = $port;
            $mail->Mailer = $mailer;

            if(isset($_POST['header']))
                $header = $_POST['header'];
            else
                $header = "";

            $reestr = DB::table('reestrs')
                    ->where('name', trim($name))
                    ->first();

            if(isset($reestr)){
                if(isset($reestr->email)){
                    $mail_to = $reestr->email;

                    $name_text = "<p>Ф.И.О.: " . $reestr->name ."</p>";

                    // dd($reestr);

                    $html_template .= '';

                    $html_template .= '';

                    $payment_reestrs = DB::table('payment_reestrs')
                                    ->select('payment_reestrs.*')
                                    ->where('reestr_id', $reestr->id)
                                    ->orderBy('year', 'desc')
                                    ->get();

                    // dd($payment_reestrs);

                    if($payment_reestrs){
                        $inc = 0;
                        foreach($payment_reestrs as $key=>$item){

                            if(isset($item->year) && isset($item->status)){

                                ++$inc;
                                $bgrow = '';
                                $reestr_title = '';
                                $btn_pay = '';
                                $status_img = '';

                                if(($inc % 2) === 0) $bgrow = 'bgcolor="#f2f2f2"';

                                if($item->status === 1){
                                    $status_text = "Оплачено";
                                    $status_img = '';
                                }elseif($item->status === 0){
                                    $status_text = "Не оплачено";
                                    $btn_pay = '';
                                    $status_img = '';
                                }

                                if($item->name == 'reestr'){
                                    $reestr_title = 'Публикация в реестре';
                                }elseif($item->name == 'membership'){
                                    $reestr_title = 'Членство';
                                }

                                $html_template .= '<tr '.$bgrow.'>'
                                                        .'<td style="padding-left:30px;padding-right:30px;">' . $status_img . '</td>'
                                                        .'<td style="padding-left:30px;padding-right:30px;">' . $item->year . '</td>'
                                                        .'<td style="padding-left:30px;padding-right:30px;">' . $reestr_title . '</td>'
                                                        .'<td style="padding-left:30px;padding-right:30px;">' . $status_text . '</td>'
                                                        .'<td style="padding-left:30px;padding-right:30px;">' . $btn_pay . '</td>'
                                                .'</tr>';

                            }

                        }
                    }

                    $html_template .= '</tbody></table></div>';

                    $html_template .= '';

                }else{
                    $response['error'] = 1;
                    $response['status'] = 'not_found_email';
                }
            }else{
                $response['error'] = 1;
                $response['status'] = 'not_found';
                $response['last_name'] = trim($request->last_name);
                $response['name'] = trim($request->name);
                $response['surname'] = trim($request->surname);
                $response['full_name'] = ncl_name_def(trim($name), 1);
            }

            // dd($payment_text);

            if($response['error'] === 0){

                $message = $html_template;

                // От кого
                $mail->setFrom($mail_from, "");
                // Кому
                $mail->addAddress($mail_to);

                // Тема письма
                $mail->Subject = $header;
                // Тело письма
                $mail->msgHTML($message);

                if($mail->send()){
                    $response['error'] = 0;
                    $response['status'] = 'success';
                    $response['email'] = obfuscate_email($reestr->email);
                } else {
                    $response['error'] = 1;
                    $response['status'] = 'error';
                    $response['error_info'] = $mail->ErrorInfo;
                }

            }

            // return view('pages.check_payment', ['response' => $response]);

            return response()->json($response, 200);

            // return redirect()->route('check_payment')->with(['response' => $response]);
        // }
    }

    public function sendMail(Request $request)
    {
        if(!empty($request->header)){
            $header = json_decode($request->header);
        }else{
            $header = null;
        }

        $data = json_decode($request->data);
        $message = json_decode($request->message);

        // dd($data);

        if(empty($message)) return ['result' => ['status' => 'error', 'info' => 'Поле "Текст" не должно быть пустым']];

        if(empty($data)) return ['result' => ['status' => 'error', 'info' => 'Нет контактных адресов для отправки']];


        $data_files = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $uploadedFile) {
                $fileName = $uploadedFile->getClientOriginalName();
                $savedPath = $uploadedFile->storeAs('uploads/tmp', $fileName); // Сохраняем файл в storage/app/uploads/tmp
                $data_files[] = [
                    'path' => storage_path('app/' . $savedPath), // Генерируем полный путь
                    'name' => $fileName
                ];
            }
        }

        $send_email = new ReestrSenderEmail($data, $header, $message, $data_files);
        $this->dispatch($send_email);

        return ['result' => ['status' => 'success', 'info' => 'Рассылка успешно отправлена']];
    }

    public function getPaymentReestr(Request $request)
    {
        $status_req = json_decode($request->payment_status);

        $payment_status = "";

        date_default_timezone_set("Europe/Moscow");
        $curr_date = date('Y-m-d');
        $date_pay = date('Y-m-d', strtotime($curr_date.' +2 month'));
        $curr_year = date('Y');

        $data = [];

        if($status_req == 'paid') {
            $data = DB::table('reestrs')
                ->select('reestrs.id')
                ->join('payment_reestrs', 'payment_reestrs.reestr_id', '=', 'reestrs.id')
                ->where('payment_reestrs.status', 1)
                ->where('payment_reestrs.year', $curr_year)
                ->get();
        }elseif($status_req == 'coming'){
            $data = DB::table('reestrs')
                ->select('reestrs.id')
                ->where('reestrs.date_end', '<=', $date_pay)
                ->where('reestrs.date_end', '>=', $curr_date)
                ->get();
        }elseif($status_req == 'none') {
            $data = DB::table('reestrs')
                ->select('reestrs.id')
                ->join('payment_reestrs', 'payment_reestrs.reestr_id', '=', 'reestrs.id')
                ->where('payment_reestrs.status', 0)
                ->where('payment_reestrs.year', $curr_year)
                ->get();
        }

        return ['reestr' => $data];
    }

    public function getReestrContacts(Request $request)
    {
        $data = json_decode($request->data);
        $reestr = Reestr::whereIn('id', $data)->get();
        return ['result' => $reestr];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reestr = Reestr::find($id);
        if($reestr) {
            $reestr->delete();
            return ["status" => true];
        }else{
            return ["status" => false];
        }
    }
}
