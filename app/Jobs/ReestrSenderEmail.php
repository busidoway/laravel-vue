<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\Reestr;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class ReestrSenderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $reestr_data_id;
    public $message;
    public $files;
    public $header;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reestr_data_id, $header, $message, $files)
    {
        $this->reestr_data_id = $reestr_data_id;
        $this->message = $message;
        $this->files = $files;
        $this->header = $header;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Log::info('Job started: ' . $this->job->getJobId());

        $domain = '';
        $host = '';
        $smtp_auth = true;
        $smtp_secure = 'ssl';
        $port = 465;
        $mailer = 'smtp';


        $username = '';
        $password = '';
        $mail_from = "";

        $mail = new PHPMailer;
        $mail->CharSet = 'UTF-8';

        // Настройки SMTP
        $mail->isSMTP();
        $mail->SMTPAuth = $smtp_auth;
        $mail->SMTPDebug = 0;

        $mail->Host = $host;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $smtp_secure;
        $mail->Port = $port;
        $mail->Mailer = $mailer;

        // Log::info('Checking files: ' . print_r($this->files, true));

        $i = 0;

        Log::info("Кол-во контактов: " . count($this->reestr_data_id));

        $tmp_reestr_data = [];

        foreach($this->reestr_data_id as $item) {

            $i++;

            $tmp_reestr_data[] = $item->id;

            $reestr = Reestr::where('id', $item->id)->first();

            if($reestr && $reestr->email){

                while (RateLimiter::tooManyAttempts('email-sending', 20)) {
                    $waitTime = RateLimiter::availableIn('email-sending');
                    Log::info("Лимит достигнут. Ждём {$waitTime} секунд.");
                    sleep($waitTime); // Ждём до сброса
                }

                // Регистрируем попытку
                RateLimiter::hit('email-sending');
                // RateLimiter::hit('email-sending-hourly');

                // Формируем сообщение
                $message_mail = $this->prepareMessage($reestr);

                // От кого
                $mail->setFrom($mail_from, "Компания");
                // Кому
                $mail->addAddress(trim($reestr->email));

                // файлы
                if (!empty($this->files)) {
                    foreach ($this->files as $file) {
                        // Log::info('Attaching file: ' . $file['path'] . ' as ' . $file['name']);
                        $mail->addAttachment($file['path'], $file['name']);
                    }
                }

                if ($this->header) {
                    // Тема письма
                    $mail->Subject = $this->header;
                }
                // Тело письма
                $mail->msgHTML($message_mail);

                try {
                    if ($mail->send()) {
                        $search_key = array_search($item->id, $tmp_reestr_data);
                        if ($search_key !== false) unset($tmp_reestr_data[$search_key]);
                        Log::info($i . ' Success send mail: ' . $reestr->email);
                    } else {
                        Log::info($i . ' Fail send mail: ' . $reestr->email);
                    }
                } catch (\Exception $e) {
                    $this->failed($e);
                    Log::info($i . ' Error send mail: ' . $reestr->email . ' desc: ' . $e);
                }

                $mail->clearAddresses();
                $mail->clearAttachments();

                // Добавляем паузу между письмами
                usleep(500000); // 0.5 секунды

            }
        }

        Log::info('Неуспешные отправления ('. count($tmp_reestr_data) .'): ');

        foreach ($tmp_reestr_data as $item) {
            Log::info('Lost id: ' . $item);
        }

        // TEST
        // От кого
        $mail->setFrom($mail_from, "Компания");
        // Кому

        // файлы
        if (!empty($this->files)) {
            foreach ($this->files as $file) {
                if (file_exists($file['path'])) {
                    $mail->addAttachment($file['path'], $file['name']);
                }
            }
        }

        if($this->header) {
            // Тема письма
            $mail->Subject = $this->header;
        }
        // Тело письма
        $mail->msgHTML($this->message);
        try {
            $mail->send();
        } catch (\Exception $e) {
            $this->failed($e);
        }

        $mail->clearAddresses();

        // Очистка временных файлов
        $this->cleanupFiles();

    }

    private function prepareMessage($reestr)
    {
        $message_mail = $this->message;

        $reestr_name = preg_replace('/\s+/', ' ', trim($reestr->name));
        $full_name = explode(" ", $reestr_name);

        $last_name = $full_name[0] ?? null;
        $first_name = $full_name[1] ?? null;
        $middle_name = $full_name[2] ?? null;
        $date_end = getDateRus($reestr->date_end);
        $date_start = getDateRus($reestr->date_start);

        $message_mail = mb_str_replace('{фамилия}', $last_name, $message_mail);
        $message_mail = mb_str_replace('{имя}', $first_name, $message_mail);
        $message_mail = mb_str_replace('{отчество}', $middle_name, $message_mail);
        $message_mail = str_replace('{email}', $reestr->email, $message_mail);
        $message_mail = str_replace('{срок аттестата}', $date_end, $message_mail);
        $message_mail = str_replace('{дата выдачи аттестата}', $date_start, $message_mail);

        return $message_mail;
    }

    private function cleanupFiles()
    {
        if (!empty($this->files)) {
            foreach ($this->files as $file) {
                if (file_exists($file['path'])) {
                    unlink($file['path']);
                    // Log::info('Deleted temporary file: ' . $file['path']);
                }
            }
        }
    }

    public function failed(Throwable $exception)
    {
        // Log::error('Job failed: ' . $this->job->getJobId());
        // return $exception;
    }
}
