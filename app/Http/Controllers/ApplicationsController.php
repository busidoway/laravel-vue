<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Reestr;
use App\Models\PaymentReestr;
use App\Models\EventApplication;
use App\Models\ProgramApp;
use App\Models\TypeProgram;
use App\Models\Intern;

class ApplicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $curr_date = date('Y-m-d H:i:s');

        $app = DB::table('events')
            ->select(DB::raw('events.id as id, events.title as title, 150 as title_count, SUBSTRING(events.title, 1, 150) as title_short, LENGTH(events.title) as title_length, COUNT(applications.id) as count_app, DATE_FORMAT(date_public, "%d.%m.%Y") as date_public'))
            ->join('event_applications', 'event_applications.event_id', '=', 'events.id')
            ->join('applications', 'applications.id', '=', 'event_applications.app_id')
            ->where('events.date_public', '>=', $curr_date)
            ->orWhere('events.date_end', '>=', $curr_date)
            ->groupBy('events.id')
            ->orderBy('events.date_public', 'desc')
            ->get();

        return ['applications' => $app];
    }

    public function getAppArchive()
    {
        $curr_date = date('Y-m-d H:i:s');

        $app = DB::table('events')
            ->select(DB::raw('
                events.id as id,
                events.title as title,
                150 as title_count,
                SUBSTRING(events.title, 1, 150) as title_short,
                LENGTH(events.title) as title_length,
                COUNT(applications.id) as count_app,
                DATE_FORMAT(date_public, "%d.%m.%Y") as date_public
            '))
            ->join('event_applications', 'event_applications.event_id', '=', 'events.id')
            ->join('applications', 'applications.id', '=', 'event_applications.app_id')
            ->where(function ($query) use ($curr_date) {
                    $query->whereNull('events.date_end')
                        ->where('events.date_public', '<', $curr_date);
            })
            ->orWhere(function ($query) use ($curr_date) {
                    $query->whereNotNull('events.date_end')
                        ->where('events.date_end', '<', $curr_date);
            })
            ->groupBy('events.id')
            ->orderBy('events.date_public', 'desc')
            ->get();

        return ['applications' => $app];
    }

    public function getAppList($event_id)
    {
        $app = DB::table('applications', 'app')
                    ->select(DB::raw("app.id as app_id,
                                            app.name_sender as app_name,
                                            app.middle_name_sender as app_middle_name,
                                            app.last_name_sender as app_last_name,
                                            app.email_sender as app_email,
                                            app.phone_sender as app_phone,
                                            app.text as app_text,
                                            DATE_FORMAT(date_send, '%d.%m.%Y %H:%i:%s') as app_date_send,
                                            app.status as app_status"))
                    ->join('event_applications', 'event_applications.app_id', '=', 'app.id')
                    ->where('event_applications.event_id', $event_id)
                    ->orderBy('app.date_send', 'desc')
                    ->get();

        $app_data = $this->getAppDataArr($app);

        return ['applications' => $app_data];
    }

    public function getProgramAppList($program_edu_id)
    {
        $app = DB::table('applications', 'app')
            ->select(DB::raw("app.id as app_id,
                                            app.name_sender as app_name,
                                            app.middle_name_sender as app_middle_name,
                                            app.last_name_sender as app_last_name,
                                            app.email_sender as app_email,
                                            app.phone_sender as app_phone,
                                            app.text as app_text,
                                            DATE_FORMAT(date_send, '%d.%m.%Y %H:%i:%s') as app_date_send,
                                            app.status as app_status"))
            ->join('program_apps', 'program_apps.application_id', '=', 'app.id')
            // ->join('programs_education', 'programs_education.id', '=', 'program_apps.programs_education_id')
            ->where('program_apps.programs_education_id', $program_edu_id)
            ->orderBy('app.date_send', 'desc')
            ->get();

        $app_data = $this->getAppDataArr($app);

        return ['applications' => $app_data];
    }

    private function getAppDataArr($app)
    {
        $app_data = [];

        foreach ($app as $item) {
            $reestr_item = [];
            $payment_reestr = [];
            $email_user = trim($item->app_email);
            $reestr = Reestr::select(DB::raw("*, DATE_FORMAT(date_end, '%d.%m.%Y') as date_end, IF(CURDATE() > date_end, true, false) as expired"))->where('email', $email_user)->first();
            if(!$reestr) {
                $name = trim($item->app_last_name) . ' ' . trim($item->app_name) . ' ' . trim($item->app_middle_name);
                $reestr_name = Reestr::select(DB::raw("*, DATE_FORMAT(date_end, '%d.%m.%Y') as date_end, IF(CURDATE() > date_end, true, false) as expired"))->where('name', 'like', '%'.trim($name).'%')->first();
                if($reestr_name){
                    $reestr_item = $reestr_name;
                    $payment_reestr = PaymentReestr::where('reestr_id', $reestr_name->id)->get();
                }

            }else{
                $reestr_item = $reestr;
                $payment_reestr = PaymentReestr::where('reestr_id', $reestr->id)->get();
            }

            $payment_reestr_arr = [];

            foreach ($payment_reestr as $pr_item) {
                $payment_reestr_arr[] = [
                    'reestr_id' => (int)$pr_item->reestr_id,
                    'name' => $pr_item->name,
                    'year' => $pr_item->year,
                    'status' => (int)$pr_item->status
                ];
            }

            $intern_item = [];

            $intern = Intern::select('id', 'position')->where('email', $email_user)->first();

            if(!$intern) {
                $full_name = trim(trim($item->app_last_name) . ' ' . trim($item->app_name) . ' ' . trim($item->app_middle_name));
                $intern_name = Intern::select('id', 'position')->where('name', 'like', '%'.$full_name.'%')->first();
                if ($intern_name) $intern_item = $intern_name;
            } else {
                $intern_item = $intern;
            }

            $app_data[] = [
                'id' => $item->app_id,
                'name_sender' => $item->app_name,
                'middle_name_sender' => $item->app_middle_name,
                'last_name_sender' => $item->app_last_name,
                'email_sender' => $item->app_email,
                'phone_sender' => $item->app_phone,
                'text' => $item->app_text,
                'date_send' => $item->app_date_send,
                'status' => $item->app_status,
                'reestr' => $reestr_item,
                'payment_reestr' => $payment_reestr_arr,
                'intern' => $intern_item
            ];

        }

        return $app_data;
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
        $data = json_decode($request->data);

        // dd($data);

        date_default_timezone_set("Europe/Moscow");
        $curr_date = date('Y-m-d H:i:s');

        $name = "";
        if(!empty($data->name)){
            $name = $data->name;
        }else{
            $name = $data->first_name;
        }

        $phone = $data->phone;
        if(!empty($data->field_req)) {
            foreach ($data->field_req as $key => $item) {
                if(stripos($key, 'Контактный номер') !== false){
                    $phone = $item;
                }
            }
        }elseif(!empty($data->field)) {
            foreach ($data->field as $key => $item) {
                if(stripos($key, 'Контактный номер') !== false){
                    $phone = $item;
                }
            }
        }

        $app = Application::create([
            "title" => $data->title,
            "name_sender" => $name,
            "middle_name_sender" => $data->middle_name,
            "last_name_sender" => $data->last_name,
            "email_sender" => $data->email,
            "phone_sender" => $phone,
            "date_send" => $curr_date,
            "status" => $data->status
        ]);

        if(!empty($data->event_id)){
            $event_app = EventApplication::create([
                "event_id" => $data->event_id,
                "app_id" => $app->id
            ]);
        }

        if(!empty($data->program_edu_id)){
            $program_app = ProgramApp::create([
                "programs_education_id" => $data->program_edu_id,
                "application_id" => $app->id
            ]);
        }

        return ['resp' => $data];
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
        //
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
        //
    }

    public function uploadAppData(Request $request)
    {
        $data_req = json_decode($request->data);

        $data = array_reverse($data_req);

        $curr_date = date('d.m.Y');

        $event_id = $request->event_id;
        $event = [];
        if(isset($event_id))
            $event = Event::select(DB::raw("id, SUBSTRING(title, 1, 50) as title, DATE_FORMAT(date_public, '%d.%m.%Y') as date_public"))->where('id', $event_id)->first();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if(!empty($data)){
            // Заголовки таблицы
            $sheet->setCellValue('A1', 'Фамилия');
            $sheet->setCellValue('B1', 'Имя');
            $sheet->setCellValue('C1', 'Отчество');
            $sheet->setCellValue('D1', 'Email');
            $sheet->setCellValue('E1', 'Телефон');
            $sheet->setCellValue('F1', 'Публикация в реестре');
            $sheet->setCellValue('G1', 'Членство в реестре');
            $sheet->setCellValue('H1', 'Срок аттестата');
            $sheet->setCellValue('I1', 'Член/запись');

            // Настройка стилей таблицы
            $styleArray = [
                'font' => [
                    'bold' => true,
                ]
            ];
            $sheet->getStyle('A1:E1')->applyFromArray($styleArray);
            $sheet->getDefaultRowDimension()->setRowHeight(-1);
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);

            // Запись данных заявок в таблицу
            foreach ($data as $key => $val) {
                $num = $key + 2;
                $sheet->setCellValue('A'.$num, $val->last_name_sender);
                $sheet->setCellValue('B'.$num, $val->name_sender);
                $sheet->setCellValue('C'.$num, $val->middle_name_sender);
                $sheet->setCellValue('D'.$num, $val->email_sender);
                $sheet->setCellValue('E'.$num, $val->phone_sender);

                $pay_reestr = "";
                $pay_member = "";
                foreach ($val->payment_reestr as $pr) {
                    if($pr->name == 'reestr') {
                        if($pr->status == 0)
                            $pay_reestr .= $pr->year . " - не оплачено\n";
                        else
                            $pay_reestr .= $pr->year . "\n";
                    } elseif($pr->name == 'membership') {
                        if($pr->status == 0)
                            $pay_member .= $pr->year . " - не оплачено\n";
                        else
                            $pay_member .= $pr->year . "\n";
                    }
                }
                $sheet->setCellValue('F'.$num, $pay_reestr);
                $sheet->setCellValue('G'.$num, $pay_member);

                $date_end = "";
                if(!empty($val->reestr)) {
                    if ($val->reestr->date_end) {
                        $date_end = $val->reestr->date_end;

                        if (strtotime($curr_date) > strtotime($date_end)) {
                            $styleBorderArray = [
                                'borders' => [
                                    'outline' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                        'color' => ['argb' => 'FFFF0000'],
                                    ],
                                ],
                            ];
                            $sheet->getStyle('H'.$num)->applyFromArray($styleBorderArray);
                        }
                    }
                }
                $sheet->setCellValue('H'.$num, $date_end);

                $membership = "";
                if (!empty($val->reestr)) {
                    if ($val->reestr->membership === 1) {
                        $membership = "Членство";
                        $styleBorderArray = [
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                    'color' => ['argb' => '10B981'],
                                ],
                            ],
                        ];
                        $sheet->getStyle('I' . $num)->applyFromArray($styleBorderArray);
                    } else {
                        $membership = "Запись";
                        $styleBorderArray = [
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                    'color' => ['argb' => '2361ce'],
                                ],
                            ],
                        ];
                        $sheet->getStyle('I' . $num)->applyFromArray($styleBorderArray);
                    }
                } elseif (!empty($val->intern)) {
                    $membership = $val->intern->position;
                    $styleBorderArray = [
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['argb' => '4b4b4b'],
                            ],
                        ],
                    ];
                    $sheet->getStyle('I' . $num)->applyFromArray($styleBorderArray);
                } else {
                    $membership = "Чужой";
                    $styleBorderArray = [
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['argb' => 'FFFF0000'],
                            ],
                        ],
                    ];
                    $sheet->getStyle('I'.$num)->applyFromArray($styleBorderArray);
                }

                $sheet->setCellValue('I'.$num, $membership);

                $sheet->getStyle('A'.$num.':H'.$num)->getAlignment()->setWrapText(true);
            }
        }

        // Выгрузка excel
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();

        // $appData = json_encode($xslData);
        $appData = json_encode('data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,'.base64_encode($xlsData));

        return ['app' => $appData, 'event' => $event];
    }

    public function searchApp(Request $request)
    {
        return ['result' => $request->data];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $app = Application::find($id);
        if($app) {
            $app->delete();
            return ['status' => 'success'];
        }else{
            return ['status' => 'error'];
        }
    }

    public function destroyEventApp($id)
    {
        $event_app = EventApplication::where('event_id', $id)->get();

        $app_id = [];
        if(!empty($event_app)){
            foreach ($event_app as $item) {
                $app_id[] = $item->app_id;
            }
            $event_app_delete = EventApplication::where('event_id', $id)->delete();
        }

        if(!empty($app_id)){
            $app = Application::whereIn('id', $app_id)->delete();
            return ['status' => 'success'];
        }else{
            return ['status' => 'empty'];
        }
    }
}
