<?php

use Illuminate\Support\Facades\DB;
use NCL\NCLNameCaseRu;

if(!function_exists('getDateRus')){
    function getDateRus($date, $date_end = null, $sep = 0) {
        $months = array(
            '01' => 'января', '02' => 'февраля', '03' => 'марта', '04' => 'апреля',
            '05' => 'мая', '06' => 'июня', '07' => 'июля', '08' => 'августа',
            '09' => 'сентября', '10' => 'октября', '11' => 'ноября', '12' => 'декабря'
        );

        $date_view = '';

        if(!is_array($date)){

            $m = date('m', strtotime($date));
            $sp = "";

            if(isset($date_end)){
                $m_end = date('m', strtotime($date_end));
                $d = date('j', strtotime($date));
                $d_end = date('j', strtotime($date_end));
                $y = date('Y', strtotime($date));
                $y_end = date('Y', strtotime($date_end));

                if($sep === 1) $sp = '<br>';

                if($y == $y_end){
                    $date_view .= 'с '.$d . ' '. $months[$m] .' '. $sp .'по '.$d_end. ' '. $months[$m_end] .' '.$y.' г.';
                    // if($m == $m_end){
                    //     $date_view .= 'с '.$d.' по '.$d_end. ' '. $months[$m] .' '.$y.' г.';
                    // }else{
                    //     $date_view .= 'с '.$d . ' '. $months[$m] .' по '.$d_end. ' '. $months[$m_end] .' '.$y.' г.';
                    // }
                }else{
                    $date_view .= 'с '.$d .' '. $months[$m] .' '.$y.' г.' .' '. $sp .'по '.$d_end.' '. $months[$m_end] .' '.$y_end.' г.';
                }

            }else{
                $date_view = date("j ".$months[$m]." Y г.", strtotime($date));
            }

        }else{

            $count = count($date);

            if($count > 1){

                // $date_list = [];

                $sp = '';

                if($sep === 1) $sp = '<br>';

                for($i = 0; $i < $count; $i++){
                    $d = date('j', strtotime($date[$i]));
                    $m = date('m', strtotime($date[$i]));
                    $y = date('Y', strtotime($date[$i]));

                    if($i == ($count - 1)){
                        $date_view .= $sp .' и ' . $d . ' ' . $months[$m] . ' ' .$y.' г.';
                    }elseif($i == ($count - 2)){
                        $date_view .= $d . ' ' . $months[$m];
                    }else{
                        $date_view .= $d . ' ' . $months[$m] . ', ';
                    }
                }

            }else{
                $date_view = implode(",", $date);
            }

        }

        return $date_view;
    }
}

if(!function_exists('getDayRus')){
    function getDayRus($date) {
        $days = array(
            'воскресенье', 'понедельник', 'вторник', 'среда',
            'четверг', 'пятница', 'суббота'
        );
        $n = date("w", strtotime($date));
        return $days[(date($n))];
    }
}

if(!function_exists('getStage')){
    function getStage($date) {
        $curr_date = date("d.m.Y");
        $date_start = date("d.m.Y", strtotime($date));
        $new_date_start = date_create($date_start);
        $new_curr_date = date_create($curr_date);
        $date_diff = date_diff($new_date_start, $new_curr_date);
        $stage_float = $date_diff->y . ',' . $date_diff->m;
        $stage = floor((float) $stage_float);
        return $stage;
    }
}

if(!function_exists('getUrlPath')){
    function getUrlPath($url){
        $str = '';
        foreach($url as $key=>$val){
            $str .= '&'.$key.'='.$val;
        }
        return $str;
    }
}

if(!function_exists('getPeopleEvent')){
    function getPeopleEvent($id){
        $event_people = DB::table('people')
                        ->select('people.last_name', 'people.name', 'people.middle_name', 'people.position', 'people.img', 'events.position_visible')
                        ->join('event_people', 'people.id', '=', 'event_people.people_id')
                        ->join('events', 'events.id', '=', 'event_people.event_id')
                        ->where('events.id', $id)
                        ->get();
        return $event_people;
    }
}

if(!function_exists('object_to_array')){
    function object_to_array($data) {

        if (is_array($data) || is_object($data)) {

            $result = [];

            foreach ($data as $key => $value) {

                $result[$key] = (is_array($data) || is_object($data)) ? object_to_array($value) : $value;

            }

            return $result;

        }

        return $data;

    }
}

if(!function_exists('ncl_name')){
    function ncl_name($last_name, $name = null, $middle_name = null, $case = null, $sex = null){
        /** Падежи
         * 0 - Именительный
         * 1 - Родительный
         * 2 - Дательный
         * 3 - Винительный
         * 4 - Творительный
         * 5 - Предложный
        */
        $nc = new NCLNameCaseRu();
        // $name = $nc->q($name, $case, $sex);
        $name = $nc->qFullName($last_name, $name, $middle_name, $sex, $case);
        return $name;
    }
}

if(!function_exists('ncl_name_def')){
    function ncl_name_def($name, $case = 1){
        /** Падежи
         * 0 - Именительный
         * 1 - Родительный
         * 2 - Дательный
         * 3 - Винительный
         * 4 - Творительный
         * 5 - Предложный
        */
        $nc = new NCLNameCaseRu();
        // $name = $nc->q($name, $case, $sex);
        $name = $nc->q($name);
        return $name[$case];
    }
}

if(!function_exists('obfuscate_email')){
    function obfuscate_email($email){
        $em   = explode("@", $email);
        $name = implode('@', array_slice($em, 0, count($em)-1));
        $len  = floor(strlen($name)/2);

        return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
    }
}

if(!function_exists('get_initial')){
    function get_initial($str){
        $str_short = "";
        if(!empty($str)){
            $str_short = mb_substr($str, 0, 1);
            $str_short .= '.';
        }
        return $str_short;
    }
}

/**
 * Функция для транслита с русских символов на английские
 */
if(!function_exists('translit_sef')){
    function translit_sef($value)
    {
        $converter = array(
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
            'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
            'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
        );

        $value = mb_strtolower($value);
        $value = strtr($value, $converter);
        $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
        $value = mb_ereg_replace('[-]+', '-', $value);
        $value = trim($value, '-');

        return $value;
    }
}

if(!function_exists('getEventVideoAccess')){
    function getEventVideoAccess($event_id, $user_id){

        date_default_timezone_set("Europe/Moscow");
        $curr_date = date('Y-m-d H:i:s');

        $user_event = DB::table('user_events')
                        ->select('user_events.*')
                        ->where('user_id', $user_id)
                        ->where('event_id', $event_id)
                        ->where('date_start', '<=', $curr_date)
                        ->where('date_end', '>', $curr_date)
                        ->first();

        // if($user_event)
        //     return true;
        // else
        //     return false;
        return $user_event;

    }
}

/**
 * Функция для замены подстроки в строке с поддержкой многобайтовых символов
 */
if(!function_exists('mb_str_replace')) {
    function mb_str_replace($search, $replace, $subject, &$count = 0) {
        if (!is_array($subject)) {
            // Замена одиночной строки
            $subject = (array)$subject;
            $single = true;
        } else {
            $single = false;
        }

        $count = 0;
        foreach ($subject as &$value) {
            $parts = explode($search, $value);
            $count += count($parts) - 1;
            $value = implode($replace, $parts);
        }

        return $single ? $subject[0] : $subject;
    }

}
