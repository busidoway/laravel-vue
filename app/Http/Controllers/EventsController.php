<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Person;
use App\Models\EventPerson;
use App\Models\Slider;
use App\Models\EventCategory;
use App\Models\EventCategoryJoin;
use App\Models\EventFormat;
use App\Models\EventFormatJoin;
use App\Models\EventVideo;
use App\Models\EventFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Validator;
use App\Models\EventSubCategoryEventJoin;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = DB::table('events')
                    ->select('events.*', 'event_categories.title as cat_title')
                    ->join('event_category_joins', 'events.id', '=', 'event_category_joins.event_id')
                    ->join('event_categories', 'event_categories.id', '=', 'event_category_joins.event_category_id')
                    ->orderBy('events.id', 'desc')
                    ->paginate(10);

        return view('admin.pages.events', ['events' => $events]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $persons = Person::all();
        $event_category = EventCategory::all();

        return view('admin.pages.event_create', ['persons' => $persons, 'event_category' => $event_category]);
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
                "title" => ["required"]
            ]
        );

        if ($validator->fails()) {
            return back()->with(["status" => false, "errors" => $validator->messages()]);
        }

        $date_public = NULL;
        $date_end = NULL;
        $date_list = NULL;

        if (isset($request->check_range_date)) {
            $date_range = explode(",", $request->date_public);
            $date_public = date("Y-m-d H:i:s", strtotime($date_range[0]));
            $date_end = date("Y-m-d H:i:s", strtotime($date_range[1]));
        } elseif (isset($request->check_multi_date)) {
            $date_list = $request->date_public;
            $date_list_arr = explode(",", $date_list);
            if(isset($date_list_arr[0])){
                $date_public = date("Y-m-d H:i:s", strtotime($date_list_arr[0]));
            }
            $date_list_last = $date_list_arr[array_key_last($date_list_arr)];
            if(isset($date_list_last)){
                $date_end = date("Y-m-d H:i:s", strtotime($date_list_last));
            }
        } elseif (isset($request->check_date)) {
            $date_public = NULL;
            $date_list = NULL;
        } else {
            $date_public = date("Y-m-d H:i:s", strtotime($request->date_public));
        }

        $img = $request->file('image');

        if($img){
            $path_img = $img->store('storage/images', 'public');
        }else{
            $path_img = null;
        }

        // dd($path_img);

        if(isset($request->position_visible))
            $pos_visible = 1;
        else
            $pos_visible = 0;

        if(isset($request->check_price_m)){
            $price_m = 0;
        }else{
            if(isset($request->price_m))
                $price_m = (int)str_replace(" ", "", $request->price_m);
            else
                $price_m = null;
        }

        if(isset($request->slider_in))
            $slider_in = 1;
        else
            $slider_in = 0;

        // if(isset($request->format))
        //     $format = $request->format . ' ' . $request->format_text;
        // else
        //     $format = $request->format_text;

        $event = Event::create([
            "title" => $request->title,
            "subtitle" => $request->subtitle,
            "cat" => $request->cat,
            "price" => $request->price,
            "price_text" => $request->price_text,
            "price_m" => $price_m,
            "date_public" => $date_public,
            "date_end" => $date_end,
            "date_list" => $date_list,
            "schedule" => $request->schedule,
            "time" => $request->time,
            "place" => $request->place,
            "period" => $request->period,
            "vol_program" => $request->vol_program,
            "format" => $request->event_format,
            "format_text" => $request->format_text,
            "short" => $request->short,
            "text" => $request->text,
            "url" => $request->url,
            "image" => $path_img,
            "position_visible" => $pos_visible,
            "slider_in" => $slider_in,
            "subtitle_slider" => $request->subtitle_slider,
            "slider_text" => $request->slider_text
        ]);

        if($request->event_category){
            $event_category_join = EventCategoryJoin::create([
                "event_id" => $event->id,
                "event_category_id" => $request->event_category
            ]);
        }

        if($request->event_sub_category){
            $event_sub_category_join = EventSubCategoryEventJoin::create([
                "event_id" => $event->id,
                "event_sub_category_id" => $request->event_sub_category
            ]);
        }

        if($request->event_person){
            $persons_data = $request->event_person;
            foreach($persons_data as $key=>$data){
                $event_person = EventPerson::create([
                    "event_id" => $event->id,
                    "people_id" => $data
                ]);
            }
        }

        if($request->event_video){
            $video_data = $request->event_video;
            foreach($video_data as $key=>$data){
                $event_video = EventVideo::create([
                    "event_id" => $event->id,
                    "video_id" => $data
                ]);
            }
        }

        // $title_slider = "Вебинар" . " " . $persons_str;
        // $date_slider = "<p>" . getDateRus($date_public) . " " . $request->time ."</p>";
        // $text1 = "<p>". $request->title ."</p>";
        // $text1_slider = $date_slider . $text1;

        // $slider = Slider::create([
        //     "title" => $title_slider,
        //     "text1" => $text1_slider
        // ]);

        return redirect()->route('events.edit', $event->id)->with(['status' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        date_default_timezone_set("Europe/Moscow");
        $curr_date = date('Y-m-d H:i:s');

        // $event = Event::find($id);

        $event = DB::table('events')
                    ->select('events.*', 'event_categories.title as cat_title', 'event_categories.code as cat_code')
                    ->join('event_category_joins', 'events.id', '=', 'event_category_joins.event_id')
                    ->join('event_categories', 'event_categories.id', '=', 'event_category_joins.event_category_id')
                    ->where('events.id', $id)
                    ->first();

        $event_category = EventCategory::all();
        $event_category_join = EventCategoryJoin::where('event_id', $event->id)->first();

        $events_data_count = Event::where('date_public', '>=', $curr_date)->where('id', '!=', $id)->count();


        if($events_data_count > 2){
            $events_data = DB::table('events')
                            ->select('events.*', 'event_categories.title as cat_title', 'event_categories.code as cat_code')
                            ->join('event_category_joins', 'events.id', '=', 'event_category_joins.event_id')
                            ->join('event_categories', 'event_categories.id', '=', 'event_category_joins.event_category_id')
                            ->where('events.date_public', '>=', $curr_date)
                            ->where('events.id', '!=', $id)
                            ->get()
                            ->random(3);
        }elseif($events_data_count > 0 && $events_data_count < 3){
            $events_data = DB::table('events')
                            ->select('events.*', 'event_categories.title as cat_title', 'event_categories.code as cat_code')
                            ->join('event_category_joins', 'events.id', '=', 'event_category_joins.event_id')
                            ->join('event_categories', 'event_categories.id', '=', 'event_category_joins.event_category_id')
                            ->where('events.date_public', '>=', $curr_date)
                            ->where('events.id', '!=', $id)
                            ->get();
        }else{
            $events_data = [];
        }

        // $events_data_rand = Arr::random($events_data, 3);

        return view('pages.inner.event_inner', ['event' => $event, 'event_category' => $event_category, 'event_category_join' => $event_category_join, 'events_data' => $events_data]);
    }

    public function getEventsVideo(Request $request)
    {
        date_default_timezone_set("Europe/Moscow");
        $curr_date = date('Y-m-d H:i:s');

        $filter_title = $request->title;
        $filter_person = $request->person;
        $filter_cat = $request->cat;
        $filter_date_from = $request->date_from;
        $filter_date_to = $request->date_to;

        $where_arr = [];
        $where_raw = '';

        if(!isset($filter_date_to) && empty($filter_date_to) && $filter_date_to <= $curr_date){
            // $where_arr[] = ['date_public', '<', $curr_date];
            $where_raw = 'date_public < "'. $curr_date . '"';
        }else{
            $date_to_req = date("Y-m-d", strtotime($filter_date_to));
            $where_raw = 'if(events.date_end is null, events.date_public <= "'.$date_to_req.'", events.date_end <= "'.$date_to_req.'")';
        }

        if(isset($filter_title) && !empty($filter_title)) $where_arr[] = ['events.title', 'like', '%'.$filter_title.'%'];
        if(isset($filter_person) && !empty($filter_person)) $where_arr[] = ['people.id', '=', $filter_person];
        if(isset($filter_cat) && !empty($filter_cat)) $where_arr[] = ['event_categories.id', '=', $filter_cat];

        if(isset($filter_date_from) && !empty($filter_date_from)) $where_arr[] = ['events.date_public', '>=', date("Y-m-d", strtotime($filter_date_from))];

        $events_video = DB::table('events')
                            ->select('events.*')
                            ->join('event_people', 'event_people.event_id', '=', 'events.id')
                            ->join('people', 'event_people.people_id', '=', 'people.id')
                            ->join('event_category_joins', 'event_category_joins.event_id', '=', 'events.id')
                            ->join('event_categories', 'event_category_joins.event_category_id', '=', 'event_categories.id')
                            ->whereRaw($where_raw)
                            ->where($where_arr)
                            ->orderByRaw('`date_public` IS NULL DESC, `date_public` DESC')
                            ->groupBy('events.id')
                            ->paginate(12);
                            // ->dd();

        $persons = DB::table('people')
                            ->select('people.*')
                            ->join('event_people', 'event_people.people_id', '=', 'people.id')
                            ->join('events', 'event_people.event_id', '=', 'events.id')
                            ->groupBy('people.id')
                            ->get();

        $event_categories = DB::table('event_categories')->get();

        // if(isset($request) && !empty($request)) {

        // }

        return view('pages.events_video', ['events_video' => $events_video, 'persons' => $persons, 'event_categories' => $event_categories, 'search' => $request]);
    }

    public function showEventVideo($id)
    {
        date_default_timezone_set("Europe/Moscow");
        $curr_date = date('Y-m-d H:i:s');

        // $event = Event::find($id);

        $event = DB::table('events')
                    ->select('events.*', 'event_categories.title as cat_title', 'event_categories.code as cat_code')
                    ->join('event_category_joins', 'events.id', '=', 'event_category_joins.event_id')
                    ->join('event_categories', 'event_categories.id', '=', 'event_category_joins.event_category_id')
                    ->where('events.id', $id)
                    ->first();

        $event_videos = DB::table('event_videos')
                    ->select('event_videos.*', 'videos.*')
                    ->join('videos', 'videos.id', '=', 'event_videos.video_id')
                    ->where('event_videos.event_id', $id)
                    ->get();

        $event_files = EventFile::where('event_id', $id)->get();

        $event_category = EventCategory::all();
        $event_category_join = EventCategoryJoin::where('event_id', $event->id)->first();

        $events_data_count = Event::where('date_public', '<', $curr_date)->where('id', '!=', $id)->count();


        if($events_data_count > 2){
            // $events_data = Event::where('date_public', '<', $curr_date)->where('id', '!=', $id)->get()->random(3);
            $events_data = DB::table('events')
                            ->select('events.*', 'event_categories.title as cat_title', 'event_categories.code as cat_code')
                            ->join('event_category_joins', 'events.id', '=', 'event_category_joins.event_id')
                            ->join('event_categories', 'event_categories.id', '=', 'event_category_joins.event_category_id')
                            ->where('events.date_public', '<', $curr_date)
                            ->where('events.id', '!=', $id)
                            ->get()
                            ->random(3);
        }elseif($events_data_count > 0 && $events_data_count < 3){
            $events_data = DB::table('events')
                            ->select('events.*', 'event_categories.title as cat_title', 'event_categories.code as cat_code')
                            ->join('event_category_joins', 'events.id', '=', 'event_category_joins.event_id')
                            ->join('event_categories', 'event_categories.id', '=', 'event_category_joins.event_category_id')
                            ->where('events.date_public', '<', $curr_date)
                            ->where('events.id', '!=', $id)
                            ->get();
        }else{
            $events_data = [];
        }

        // $events_data_rand = Arr::random($events_data, 3);

        return view('pages.inner.event_video_inner', ['event' => $event, 'event_videos' => $event_videos, 'event_files' => $event_files, 'event_category' => $event_category, 'event_category_join' => $event_category_join, 'events_data' => $events_data]);
    }

    public function viewEventVideo($id)
    {
        date_default_timezone_set("Europe/Moscow");
        $curr_date = date('Y-m-d H:i:s');

        // $event = Event::find($id);

        $event = DB::table('events')
                    ->select('events.*', 'event_categories.title as cat_title', 'event_categories.code as cat_code')
                    ->join('event_category_joins', 'events.id', '=', 'event_category_joins.event_id')
                    ->join('event_categories', 'event_categories.id', '=', 'event_category_joins.event_category_id')
                    ->where('events.id', $id)
                    ->first();

        $event_videos = DB::table('event_videos')
                    ->select('event_videos.*', 'videos.*')
                    ->join('videos', 'videos.id', '=', 'event_videos.video_id')
                    ->where('event_videos.event_id', $id)
                    ->get();

        $event_files = EventFile::where('event_id', $id)->get();

        $event_category = EventCategory::all();
        $event_category_join = EventCategoryJoin::where('event_id', $event->id)->first();

        $events_data_count = Event::where('date_public', '>=', $curr_date)->where('id', '!=', $id)->count();


        if($events_data_count > 2){
            $events_data = Event::where('date_public', '>=', $curr_date)->where('id', '!=', $id)->get()->random(3);
        }elseif($events_data_count > 0 && $events_data_count < 3){
            $events_data = Event::where('date_public', '>=', $curr_date)->where('id', '!=', $id)->get();
        }else{
            $events_data = [];
        }

        // $events_data_rand = Arr::random($events_data, 3);

        return view('pages.inner.event_video_view', ['event' => $event, 'event_videos' => $event_videos, 'event_files' => $event_files, 'event_category' => $event_category, 'event_category_join' => $event_category_join, 'events_data' => $events_data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $events = Event::find($id);
        // $event_person = EventPerson::where('event_id', $events->id)->first();
        // $persons = Person::all();

        $event_person = DB::table('people')
                    ->select('people.*')
                    ->join('event_people', 'people.id', '=', 'event_people.people_id')
                    ->where('event_people.event_id', $id)
                    ->get();

        $arr_persons = array();

        foreach($event_person as $ep){
            $arr_persons[] = $ep->id;
        }

        // return $arr_users;

        $persons = DB::table('people')
                    ->whereNotIn('id', $arr_persons)
                    ->get();

        $event_category = EventCategory::all();
        $event_category_join = EventCategoryJoin::where('event_id', $events->id)->first();
        $event_sub_category_join = EventSubCategoryEventJoin::where('event_id', $events->id)->first();

        return view(
            'admin.pages.event_edit',
            [
                'events' => $events,
                'persons' => $persons,
                'event_person' => $event_person,
                'event_category' => $event_category,
                'event_category_join' => $event_category_join,
                'event_sub_category_join' => $event_sub_category_join
            ]);
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
                "title" => ["required"]
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with(["status" => false, "errors" => $validator->messages()]);
        }

        $events = Event::find($id);

        $date_public = NULL;
        $date_end = NULL;
        $date_list = NULL;

        if(isset($request->check_range_date)){
            $date_range = explode(",", $request->date_public);
            $date_public = date("Y-m-d H:i:s", strtotime($date_range[0]));
            $date_end = date("Y-m-d H:i:s", strtotime($date_range[1]));
        }elseif(isset($request->check_multi_date)){
            $date_list = $request->date_public;
            $date_list_arr = explode(",", $date_list);
            if(isset($date_list_arr[0])){
                $date_public = date("Y-m-d H:i:s", strtotime($date_list_arr[0]));
            }
            $date_list_last = $date_list_arr[array_key_last($date_list_arr)];
            if(isset($date_list_last)){
                $date_end = date("Y-m-d H:i:s", strtotime($date_list_last));
            }
        }elseif(isset($request->check_date)){
            $date_public = NULL;
            $date_list = NULL;
        }else{
            $date_public = date("Y-m-d H:i:s", strtotime($request->date_public));
        }

        if(isset($request->check_price_m)){
            $price_m = 0;
        }else{
            if(isset($request->price_m))
                $price_m = (int)str_replace(" ", "", $request->price_m);
            else
                $price_m = null;
        }

        $events->title = $request->title;
        $events->subtitle = $request->subtitle;
        $events->cat = $request->cat;
        $events->price = $request->price;
        $events->price_text = $request->price_text;
        $events->price_m = $price_m;
        $events->date_public = $date_public;
        $events->date_end = $date_end;
        $events->date_list = $date_list;
        $events->schedule = $request->schedule;
        $events->time = $request->time;
        $events->place = $request->place;
        $events->period = $request->period;
        $events->vol_program = $request->vol_program;
        $events->format = $request->event_format;
        $events->format_text = $request->format_text;
        $events->short = $request->short;
        $events->text = $request->text;
        $events->url = $request->url;

        $img = $request->file('image');

        if($img){
            $events->image = $img->store('images', 'public');
        }

        if(isset($request->position_visible))
            $events->position_visible = 1;
        else
            $events->position_visible = 0;

        if(isset($request->slider_in))
            $events->slider_in = 1;
        else
            $events->slider_in = 0;

        $events->subtitle_slider = $request->subtitle_slider;
        $events->slider_text = $request->slider_text;

        if($events->isDirty()){
            $events->save();
        }

        if(isset($request->event_person)){

            $persons_data = $request->event_person;
            $persons_data_keys = [];

            // dd($persons_data);

            foreach($persons_data as $key=>$data){
                $event_person = EventPerson::where('people_id', $data)->where('event_id', $id)->first();
                // dd($event_person);
                if($event_person){
                    $event_person->people_id = $data;
                    $event_person->event_id = $id;
                    if($event_person->isDirty()){
                        $event_person->save();
                    }
                    // $persons_data_keys[] = $event_person->id;
                }else{
                    $event_person = EventPerson::create([
                        "people_id" => $data,
                        "event_id" => $id
                    ]);
                    // $persons_data_keys[] = $event_person->id;
                }

            }

            if(!empty($persons_data)){
                $event_person_empty = EventPerson::where('event_id', $id)
                                             ->whereNotIn('people_id', $persons_data)
                                             ->get();

                foreach($event_person_empty as $uv){
                    $event_person_delete = EventPerson::find($uv->id);
                    if($event_person_delete) $event_person_delete->delete();
                }
            }

        }

        if(isset($request->event_video)){

            $video_data = $request->event_video;

            foreach($video_data as $key=>$data){
                $event_video = EventVideo::where('video_id', $data)->where('event_id', $id)->first();
                // dd($event_person);
                if($event_video){
                    $event_video->video_id = $data;
                    $event_video->event_id = $id;
                    if($event_video->isDirty()){
                        $event_video->save();
                    }
                }else{
                    $event_video = EventVideo::create([
                        "video_id" => $data,
                        "event_id" => $id
                    ]);
                }

            }

            if(!empty($video_data)){
                $event_video_empty = EventVideo::where('event_id', $id)
                                             ->whereNotIn('video_id', $video_data)
                                             ->get();

                foreach($event_video_empty as $uv){
                    $event_video_delete = EventVideo::find($uv->id);
                    if($event_video_delete) $event_video_delete->delete();
                }
            }

        }

        if($request->event_category){
            $event_category_join = EventCategoryJoin::where('event_id', $events->id)->first();

            if($event_category_join){
                $event_category_join->event_category_id = $request->event_category;
                if($event_category_join->isDirty()){
                    $event_category_join->save();
                }
            }else{
                $event_category_join_create = EventCategoryJoin::create([
                    "event_id" => $events->id,
                    "event_category_id" => $request->event_category
                ]);
            }
        }

        if ($request->event_sub_category) {
            $event_sub_category_join = EventSubCategoryEventJoin::where('event_id', $events->id)->first();

            if ($event_sub_category_join) {
                $event_sub_category_join->event_sub_category_id = $request->event_sub_category;
                if ($event_sub_category_join->isDirty()) {
                    $event_sub_category_join->save();
                }
            } else {
                $event_sub_category_join_create = EventSubCategoryEventJoin::create([
                    "event_id" => $events->id,
                    "event_sub_category_id" => $request->event_sub_category
                ]);
            }
        } else {
            $event_sub_category_join = EventSubCategoryEventJoin::where('event_id', $events->id)->first();
            if ($event_sub_category_join) {
                $event_sub_category_join->delete();
            }
        }

        return redirect()->back()->with(['status' => true]);

    }

    public function getPerson()
    {
        $persons = Person::all();

        return ['persons' => $persons];
    }

    public function getEventPerson(Event $event)
    {
        $event_person = DB::table('people')
                        ->select('people.*', 'event_people.id as ep_id')
                        ->join('event_people', 'people.id', '=', 'event_people.people_id')
                        ->where('event_people.event_id', $event->id)
                        ->get();

        $arr_persons = array();

        foreach($event_person as $ep){
            $arr_persons[] = $ep->id;
        }

        $persons = DB::table('people')
                    ->whereNotIn('id', $arr_persons)
                    ->get();

        $position_visible = Event::select('position_visible')->where('id', $event->id)->first();

        return ['persons' => $persons, 'event' => $event, 'event_person' => $event_person, 'position_visible' => $position_visible];
    }

    public function getCheckEventsList(Request $request)
    {
        $event_data = json_decode($request->check_events);
        $events = Event::whereIn('id', $event_data)->get();

        return ['events' => $events];
    }

    public function getEventDate($event_id)
    {
        $event_date = Event::select(DB::raw("date_public, date_end, date_list, DATE_FORMAT(date_public, '%d.%m.%Y %H:%i:%s') as date_public_format, DATE_FORMAT(date_end, '%d.%m.%Y %H:%i:%s') as date_end_format"))->find($event_id);

        $event_date_list = Event::select('date_list')->find($event_id);

        $date_list = [];

        if($event_date_list){
            $date_list_arr = explode(",", $event_date_list->date_list);
            foreach($date_list_arr as $date){
                $date_list[] = date("Y-m-d H:i:s", strtotime(trim($date)));
            }
        }

        return ['event_date' => $event_date, 'date_list' => $date_list];
    }

    public function getEventVideo($event_id)
    {
        $event_video = DB::table('videos')
                    ->select('videos.*', 'event_videos.event_id as ev_id')
                    ->join('event_videos', 'videos.id', '=', 'event_videos.video_id')
                    ->where('event_videos.event_id', $event_id)
                    ->get();

        return ['event_video' => $event_video];
    }

    public function getEventTitle($id)
    {
        $event = Event::select(DB::raw('title, DATE_FORMAT(date_public, "%d.%m.%Y") as date_public'))->where('id', $id)->first();

        return ['event_title' => strip_tags($event->title), 'event_date_public' => $event->date_public];
    }

    public function deleteEventVideo($id)
    {
        $event_video = EventVideo::find($id);
        if($event_video) {
            $event_video->delete();
            return ['delete' => 'success'];
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
        $events = Event::find($id);
        if($events) {
            $events->delete();
            DB::table('event_category_joins')->where('event_id', $id)->delete();
            return redirect()->route('admin.events')->with(["status" => true]);
        }else{
            return redirect()->route('admin.events')->with(["status" => false]);
        }
    }
}
