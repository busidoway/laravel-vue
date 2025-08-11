<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use ReCaptcha;
use Illuminate\Http\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\DB;

class FormFeedback extends Controller
{
    public function index(Request $request){

        $secret = '';
        $host = '';
        $domain = '';
        $secure = PHPMailer::ENCRYPTION_SMTPS;
        $mailer = 'smtp';
        $port = 465;

        $username = '';
        $password = '';
        $mail_from = "";
        $mail_to = "";

        $mail = new PHPMailer;
        $mail->CharSet = 'UTF-8';

        // Настройки SMTP
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPDebug = 0;

        $mail->Host = $host;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $secure;
        $mail->Port = $port;
        $mail->Mailer = $mailer;

        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }else{
            $id = "";
        }

        if(isset($_POST['header']))
            $header = $_POST['header'];
        else
            $header = "";

        if(isset($_POST['date'])){
            $date = $_POST['date'];
        }else{
            $date = "";
        }

        if(isset($_POST['time'])){
            $time = $_POST['time'];
        }else{
            $time = "";
        }

        if(isset($_POST['cat'])){
            $cat = $_POST['cat'];
        }else{
            $cat = "";
        }

        if(isset($_POST['price'])){
            $price = $_POST['price'];
        }else{
            $price = "";
        }

        if($date){
            $date_title = "<p>" . $date . "</p>";
            $date_text = "<p>Дата: " . $date . "</p>";
        }else{
            $date_title = "";
            $date_text = "";
        }

        $data_log_title = '';

        if(!empty($_POST['title'])){
            $title = $date_title . "\nТема: ".$_POST['title'];
            $only_title = $_POST['title'];
            $data_log_title = $_POST['title'];
        }else{
            $title = "";
        }

        $response = array();

        $response['status'] = '';

        // $name = $_POST['name'];
        // $phone = $_POST['phone'];
        // $email = $_POST['email'];

        $field_arr = [];
        $field_text = "";

        if(isset($_POST['field'])){
            $field_arr = $_POST['field'];
            foreach($field_arr as $key=>$val){
                $field_text .= "<p>". $key . ": " . $val . "</p>";
            }
        }

        $field_req_arr = [];
        $field_req_text = "";

        if(isset($_POST['field_req'])){
            $field_req_arr = $_POST['field_req'];
            foreach($field_req_arr as $key=>$val){
                if(empty($val)){
                    $response['status'] = 'error';
                    $response['error_input'] = 'field_req['.$key.']';
                }else $field_req_text .= "<p>". $key . ": " . $val . "</p>";
            }
        }

        $data_log_phone = '';

        if(isset($_POST['phone'])){
            if(empty($_POST['phone'])) {
                $response['status'] = 'error';
                $response['error_input'] = 'phone';
            }else{
                $phone = "<p>Телефон: ".$_POST['phone']."</p>";
                $data_log_phone = $_POST['phone'];
            }
        }else $phone = "";

        $data_log_email = '';

        if(isset($_POST['email'])){
            if(empty($_POST['email'])) {
                $response['status'] = 'error';
                $response['error_input'] = 'email';
            }else{
                $email = "<p>E-mail: ".$_POST['email']."</p>";
                $user_email = $_POST['email'];
                $data_log_email = $_POST['email'];
            }
        }else $email = "";

        $data_log_middle_name = '';

        if(isset($_POST['middle_name'])){
            if(empty($_POST['middle_name'])) {
                $response['status'] = 'error';
                $response['error_input'] = 'middle_name';
            }else{
                $middle_name = "<p>Отчество: " . $_POST['middle_name']."</p>";
                $data_log_middle_name = $_POST['middle_name'];
            }
        }else $middle_name = "";

        $data_log_first_name = '';

        if(isset($_POST['first_name'])){
            if(empty($_POST['first_name'])) {
                $response['status'] = 'error';
                $response['error_input'] = 'first_name';
            }else{
                $first_name = "<p>Имя: " . $_POST['first_name']."</p>";
                $data_log_first_name = $_POST['first_name'];
            }
        }else $first_name = "";

        $data_log_last_name = '';

        if(isset($_POST['last_name'])){
            if(empty($_POST['last_name'])) {
                $response['status'] = 'error';
                $response['error_input'] = 'last_name';
            }else{
                $last_name = "<p>Фамилия: " . $_POST['last_name']."</p>";
                $data_log_last_name = $_POST['last_name'];
            }
        }else $last_name = "";

        $data_log_name = '';

        if(isset($_POST['name'])){
            if(empty($_POST['name'])) {
                $response['status'] = 'error';
                $response['error_input'] = 'name';
            }else{
                $name = "<p>Ф.И.О.: " . $_POST['name']."</p>";
                $data_log_name = $_POST['name'];
            }
        }else $name = "";

        if(isset($_FILES['myfile']))
            $file = $_FILES['myfile'];
        else
            $file = "";

        if (!empty($file['name'][0])) {
            for ($ct = 0; $ct < count($file['tmp_name']); $ct++) {
                $uploadfile = tempnam(sys_get_temp_dir(), sha1($file['name'][$ct]));
                $filename = $file['name'][$ct];
                if (move_uploaded_file($file['tmp_name'][$ct], $uploadfile)) {
                    $mail->addAttachment($uploadfile, $filename);
                } else {
                    $response['status'] = 'error';
                    $response['error_input'] = "Не удалось прикрепить файл $filename";
                }
            }
        }


        if(isset($_POST['license'])){
            if(!isset($_POST['check_license'])) {
                $response['status'] = 'error';
                $response['error_input'] = 'check_license';
                $response['error_text'] = 'Необходимо принять условия';
            }
        }

        $org_email = "";

        if(isset($_POST['program_edu'])){
            $org_email = DB::table('organizations')->select('email')->where('id', $id)->first();
            $response['program_edu'] = $_POST['program_edu'];
        }

        if (isset($_POST['event_id'])) {
            $event_id = $_POST['event_id'];
        } else {
            $event_id = "";
        }

        if (isset($_POST['program_edu_id'])) {
            $program_edu_id = $_POST['program_edu_id'];
        } else {
            $program_edu_id = "";
        }

        if (isset($_POST['g-recaptcha-response'])) {

            $recaptcha = new \ReCaptcha\ReCaptcha($secret);

            $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

            if ($resp->isSuccess()){

                if($response['status'] != 'error'){

                    $message = $title . $date_text . $name . $last_name . $first_name . $middle_name . $phone . $email . $field_req_text . $field_text;

                    // От кого
                    $mail->setFrom($mail_from, $domain);
                    // Кому
                    $mail->addAddress($mail_to);

                    if(!empty($org_email->email)) {
                        $mail->addAddress($org_email->email);
                    }

                    // Тема письма
                    $mail->Subject = $header;
                    // Тело письма
                    $mail->msgHTML($message);

                    /*
                     * Обозначения кодов статуса
                     * 1 - успешно отправлено
                     * 0 - ошибка отправки
                     */

                    if($mail->send()){
                        $response['status'] = 'success';
                        $response['status_code'] = 1;
                    } else {
                        $response['status'] = 'error';
                        $response['status_code'] = 0;
                        $response['error_info'] = $mail->ErrorInfo;
                    }

                    $data_send_log = [
                        'id' => $id,
                        'event_id' => $event_id,
                        'program_edu_id' => $program_edu_id,
                        'name' => $data_log_name,
                        'first_name' => $data_log_first_name,
                        'middle_name' => $data_log_middle_name,
                        'last_name' => $data_log_last_name,
                        'email' => $data_log_email,
                        'phone' => $data_log_phone,
                        'title' => $data_log_title,
                        'field' => $field_arr,
                        'field_req' => $field_req_arr,
                        'status' => $response['status_code']
                    ];

                    $response['data_send'] = $data_send_log;

                    $mail->clearAddresses();

                    if(isset($_POST['event'])){

                        $cat_header = "";
                        $cat_info = "";
                        $cat_text = "";
                        $cat_theme1 = "";
                        $cat_theme2 = "";

                        if($cat == "webinar"){
                            $cat_header = "Вебинар";
                            $cat_info = "Вебинар";
                            $cat_text = "вебинара";
                            $cat_theme1 = "Вас пригласили на вебинар";
                            $cat_theme2 = "Напоминание о вебинаре";
                        }elseif($cat == "online-course"){
                            $cat_header = "Онлайн-курс";
                            $cat_info = "Онлайн-курс";
                            $cat_text = "онлайн-курса";
                            $cat_theme1 = "Вас пригласили на онлайн-курс";
                            $cat_theme2 = "Напоминание об онлайн-курсе";
                        }elseif($cat == "videokurs"){
                            $cat_header = "Видеокурс";
                            $cat_info = "Видеокурс";
                            $cat_text = "видеокурса";
                            $cat_theme1 = "Вас пригласили на видеокурс";
                            $cat_theme2 = "Напоминание о видеокурсе";
                        }elseif($cat == "seminar-vebinar"){
                            $cat_header = "Семинар / Вебинар";
                            $cat_info = "Семинар / Вебинар";
                            $cat_text = "семинара / вебинара";
                            $cat_theme1 = "Вас пригласили на семинар / вебинар";
                            $cat_theme2 = "Напоминание о семинаре / вебинаре";
                        }elseif($cat == "stazhirovka"){
                            $cat_header = "Стажировку";
                            $cat_info = "Стажировка";
                            $cat_text = "стажировки";
                            $cat_theme1 = "Вас пригласили на стажировку";
                            $cat_theme2 = "Напоминание о стажировке";
                        }elseif($cat == "onlayn-vstrecha"){
                            $cat_header = "Онлайн-встречу";
                            $cat_info = "Онлайн-встреча";
                            $cat_text = "онлайн-встречи";
                            $cat_theme1 = "Вас пригласили на онлайн-встречу";
                            $cat_theme2 = "Напоминание об онлайн-встрече";
                        }elseif($cat == "praktikum"){
                            $cat_header = "Практикум";
                            $cat_info = "Практикум";
                            $cat_text = "практикума";
                            $cat_theme1 = "Вас пригласили на практикум";
                            $cat_theme2 = "Напоминание о практикуме";
                        }

                        $period_text = "5 дней";

                        if($cat == "online-course" || $cat == "videokurs" || $cat == "stazhirovka"){
                            $period_text = "30 дней";
                        }else{
                            if($price == 0){
                                $period_text = "5 дней";
                            }else{
                                $period_text = "10 дней";
                            }
                        }

                        $user_mess = '<p></p>';
                        // От кого
                        $mail->setFrom($mail_from, $domain);
                        // Кому
                        $mail->addAddress($user_email);

                        // Тема письма
                        $mail->Subject = 'Вы успешно зарегистрированы на '. $cat_header;
                        // Тело письма
                        $mail->msgHTML($user_mess);

                        /*
                         * Обозначения кодов статуса
                         * 1 - успешно отправлено
                         * 0 - ошибка отправки
                         */

                        if($mail->send()){
                            $response['status'] = 'success';
                            $response['status_code'] = 1;
                        } else {
                            $response['status'] = 'error';
                            $response['status_code'] = 0;
                            $response['error_info'] = $mail->ErrorInfo;
                        }

                    }

                }

                $response['captcha'] = 'success';

            }else{
                $errors = $resp->getErrorCodes();
                $response['error-captcha'] = $errors;
                $response['msg'] = 'Код капчи не прошёл проверку на сервере';
                $response['captcha'] = 'error';
            }

        }else{
            $response['msg'] = 'Ошибка проверки';
            $response['captcha'] = 'error';
        }

        // $msg = "This is a simple message.";
        return response()->json($response, 200);

    }

    // }
}
