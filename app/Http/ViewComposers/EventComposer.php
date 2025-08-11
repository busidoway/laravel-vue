<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Pagination\Paginator;
use App\Models\Event;
use App\Models\EventPerson;
use Illuminate\Support\Facades\DB;

class EventComposer
{
    public function compose(View $view)
    {
        $event_arr = array();
        $event_people = array();
        date_default_timezone_set("Europe/Moscow");
        $curr_date = date('Y-m-d H:i:s');

        // $event_arr['events'] = Event::orderBy('date_public', 'asc')->whereDate('date_public', '>=', $curr_date)->get();

        $event_arr['events'] = DB::table('events')
                    ->select(DB::raw('events.*, event_categories.title AS cat_title, event_categories.code AS cat_code, DATE_SUB(events.date_public, INTERVAL 2 HOUR)'))
                    // ->select('events.*', 'people.name', 'people.position', 'people.img')
                    // ->join('event_people', 'events.id', '=', 'event_people.event_id')
                    // ->join('people', 'people.id', '=', 'event_people.people_id')
                    ->join('event_category_joins', 'events.id', '=', 'event_category_joins.event_id')
                    ->join('event_categories', 'event_categories.id', '=', 'event_category_joins.event_category_id')
                    ->where('date_public', '>=', $curr_date)
                    ->orWhere('date_end', '>=', $curr_date)
                    ->orWhere('date_public', null)
                    // ->orderBy('date_public', 'asc')
                    ->orderByRaw('`date_public` asc, `date_public` IS NULL asc')
                    ->get();

        $event_arr['events_archive'] = DB::table('events')
                    ->select('events.*', 'event_categories.title AS cat_title', 'event_categories.code AS cat_code')
                    ->join('event_category_joins', 'events.id', '=', 'event_category_joins.event_id')
                    ->join('event_categories', 'event_categories.id', '=', 'event_category_joins.event_category_id')
                    ->orderByRaw('`date_public` IS NULL DESC, `date_public` DESC')
                    ->paginate(12);

        // DB::enableQueryLog();



        // Мероприятия в слайдере

        $subcat_zero = DB::table('events')
            ->select('event_sub_category_event_joins.event_sub_category_id as subcat_id', DB::raw('min(events.date_public) as min_date_public'))
            ->join('event_sub_category_event_joins', 'events.id', '=', 'event_sub_category_event_joins.event_id')
            // ->join('event_sub_category_joins', 'event_sub_category_event_joins.event_sub_category_id', '=', 'event_sub_category_joins.event_sub_category_id')
            ->where('events.date_public', '>=', $curr_date)
            ->where(function ($q) {
                $q->where('events.price_m', '=', 0)
                    ->orWhereNull('events.price_m');
            })
            ->groupBy('event_sub_category_event_joins.event_sub_category_id');

        $subcat_pos = DB::table('events')
            ->select('event_sub_category_event_joins.event_sub_category_id as subcat_id', DB::raw('min(events.date_public) as min_date_public'))
            ->join('event_sub_category_event_joins', 'events.id', '=', 'event_sub_category_event_joins.event_id')
            // ->join('event_sub_category_joins', 'event_sub_category_event_joins.event_sub_category_id', '=', 'event_sub_category_joins.event_sub_category_id')
            ->where('events.date_public', '>=', $curr_date)
            ->where('events.price_m', '>', 0)
            ->groupBy('event_sub_category_event_joins.event_sub_category_id');

        $slider_sub_zero = DB::table('events')
            ->select(DB::raw("
                events.id         AS event_id,
                events.title      AS event_title,
                events.subtitle   AS event_subtitle,
                events.date_public,
                events.date_end,
                events.date_list,
                events.time,
                events.subtitle_slider,
                events.slider_text,
                event_sub_categories.id    AS subcat_id,
                event_sub_categories.title AS subcat_title
            "))
            ->join('event_sub_category_event_joins', 'events.id', '=', 'event_sub_category_event_joins.event_id')
            ->join('event_sub_categories', 'event_sub_category_event_joins.event_sub_category_id', '=', 'event_sub_categories.id')
            // ->join('event_sub_category_joins', 'event_sub_category_event_joins.event_sub_category_id', '=', 'event_sub_category_joins.event_sub_category_id')
            // ->join('event_categories', 'event_sub_category_event_joins.event_category_id', '=', 'event_categories.id')
            ->joinSub($subcat_zero, 'subcat_min', function ($join) {
                $join->on('event_sub_categories.id', '=', 'subcat_min.subcat_id')
                    ->on('events.date_public', '=', 'subcat_min.min_date_public');
            })
            ->where('events.slider_in', 1);

        $slider_sub_pos = DB::table('events')
            ->select(DB::raw("
                events.id         AS event_id,
                events.title      AS event_title,
                events.subtitle   AS event_subtitle,
                events.date_public,
                events.date_end,
                events.date_list,
                events.time,
                events.subtitle_slider,
                events.slider_text,
                event_sub_categories.id    AS subcat_id,
                event_sub_categories.title AS subcat_title
            "))
            ->join('event_sub_category_event_joins', 'events.id', '=', 'event_sub_category_event_joins.event_id')
            ->join('event_sub_categories', 'event_sub_category_event_joins.event_sub_category_id', '=', 'event_sub_categories.id')
            // ->join('event_categories', 'event_sub_category_event_joins.event_category_id', '=', 'event_categories.id')
            // ->join('event_sub_category_joins', 'event_sub_category_event_joins.event_sub_category_id', '=', 'event_sub_category_joins.event_sub_category_id')
            ->joinSub($subcat_pos, 'subcat_min', function ($join) {
                $join->on('event_sub_categories.id', '=', 'subcat_min.subcat_id')
                    ->on('events.date_public', '=', 'subcat_min.min_date_public');
            })
            ->where('events.slider_in', 1);

        $event_cat_zero = DB::table('events')
                    ->select('event_categories.id as cat_id', DB::raw('min(events.date_public) as min_date_public'))
                    ->join('event_category_joins', 'events.id', '=', 'event_category_joins.event_id')
                    ->join('event_categories', 'event_categories.id', '=', 'event_category_joins.event_category_id')
                    ->where('events.date_public', '>=', $curr_date)
                    ->where(function ($query) {
                        $query->where('events.price_m', '=', 0)
                            ->orWhereNull('events.price_m');
                    })
                    ->groupBy('event_categories.id');

        $event_cat_pos = DB::table('events')
                    ->select('event_categories.id as cat_id', DB::raw('min(events.date_public) as min_date_public'))
                    ->join('event_category_joins', 'events.id', '=', 'event_category_joins.event_id')
                    ->join('event_categories', 'event_categories.id', '=', 'event_category_joins.event_category_id')
                    ->where('events.date_public', '>=', $curr_date)
                    ->where('events.price_m', '>', 0)
                    ->groupBy('event_categories.id');

        $slider_query_zero = DB::table('events')
            ->select(DB::raw("
                events.id           AS event_id,
                events.title        AS event_title,
                events.subtitle     AS event_subtitle,
                events.date_public,
                events.date_end,
                events.date_list,
                events.time,
                events.subtitle_slider,
                events.slider_text,
                NULL                AS subcat_id,
                NULL                AS subcat_title,
                event_categories.id AS cat_id,
                event_categories.title AS cat_title,
                event_categories.code  AS cat_code
            "))
                    ->join('event_category_joins', 'events.id', '=', 'event_category_joins.event_id')
                    ->join('event_categories', 'event_categories.id', '=', 'event_category_joins.event_category_id')
                    // Отсекаем все events, у которых есть любая sub-category
                    ->whereNotExists(function($q) {
                        $q->select(DB::raw(1))
                            ->from('event_sub_category_event_joins')
                            ->whereColumn('event_sub_category_event_joins.event_id', 'events.id');
                    })
                    ->joinSub($event_cat_zero, 'events_cat', function ($join) {
                        $join->on('event_categories.id', '=', 'events_cat.cat_id')
                            ->on('events.date_public', '=', 'events_cat.min_date_public');
                    })
                    ->where('events.slider_in', 1);

        // ->orderBy('events.date_public', 'asc')
                    // ->get();

        $slider_query_pos = DB::table('events')
            ->select(DB::raw("
                events.id           AS event_id,
                events.title        AS event_title,
                events.subtitle     AS event_subtitle,
                events.date_public,
                events.date_end,
                events.date_list,
                events.time,
                events.subtitle_slider,
                events.slider_text,
                NULL                AS subcat_id,
                NULL                AS subcat_title,
                event_categories.id AS cat_id,
                event_categories.title AS cat_title,
                event_categories.code  AS cat_code
            "))
                    ->join('event_category_joins', 'events.id', '=', 'event_category_joins.event_id')
                    ->join('event_categories', 'event_categories.id', '=', 'event_category_joins.event_category_id')
                    // Тоже отсечём все с привязанными sub-join
                    ->whereNotExists(function($q) {
                        $q->select(DB::raw(1))
                            ->from('event_sub_category_event_joins')
                            ->whereColumn('event_sub_category_event_joins.event_id', 'events.id');
                    })
                    ->joinSub($event_cat_pos, 'events_cat', function ($join) {
                        $join->on('event_categories.id', '=', 'events_cat.cat_id')
                            ->on('events.date_public', '=', 'events_cat.min_date_public');
                    })
                    ->where('events.slider_in', 1);


        $union_slider_query = $slider_sub_zero
                                ->unionAll($slider_sub_pos);
                                // ->unionAll($slider_query_zero)
                                // ->unionAll($slider_query_pos);

        $result_slider_query = DB::table(DB::raw("({$union_slider_query->toSql()}) as union_table"))
            ->mergeBindings($union_slider_query) // объединяем биндинги
            ->orderBy('date_public', 'asc')
            ->get();

        $event_arr['slider'] = $result_slider_query;

        // dump(DB::getQueryLog());

        return $view->with('events', $event_arr);
    }
}
