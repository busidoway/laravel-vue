<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventFormat;
use Illuminate\Support\Facades\DB;
use Validator;

class EventFormatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $event_format = EventFormat::orderBy('id', 'desc')->paginate(10);

        return view('admin.pages.event_format', ['event_format' => $event_format]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.event_format_create');
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
                // "code" => ["required"]
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with(["status" => false, "errors" => $validator->messages()]);
        }

        if(isset($request->code)){
            $code = $request->code;
        }else{
            $code = translit_sef($request->title);
        }

        $event_format = EventFormat::create([
            "title" => $request->title,
            "code" => $code,
            "text" => $request->text
        ]);

        return redirect()->route('event_format.edit', $event_format->id)->with(['status' => true]);
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
        $event_format = EventFormat::find($id);

        return view('admin.pages.event_format_edit', ['event_format' => $event_format]);
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

        $event_format = EventFormat::find($id);

        $event_format->title = $request->title;

        if(isset($request->code)){
            $event_format->code = $request->code;
        }else{
            $event_format->code = translit_sef($request->title);
        }

        if($event_format->isDirty()){
            $event_format->save();
        }

        return redirect()->back()->with(['status' => true]);
    }

    public function getEventFormat(Request $request)
    {

        $event_format = [];
        $event_format_checklist = [];

        if($request->id){

            $event_format = DB::table('event_formats')
                    ->select('event_formats.id', 'event_formats.title', 'event_formats.code', 'event_format_joins.text')
                    ->join('event_format_joins', 'event_formats.id', '=', 'event_format_joins.event_format_id')
                    ->join('events', 'events.id', '=', 'event_format_joins.event_id')
                    ->where('events.id', $request->id)
                    ->get();
                
            return ['event_format_list' => $event_format];

        }elseif($request->format_not_list){

            $format_not_in = [];

            if(isset($request->format_not_list)) $format_not_in = json_decode($request->format_not_list);

            $event_format_checklist = EventFormat::whereNotIn('id', $format_not_in)->orderBy('id', 'desc')->get();

            return ['event_format_checklist' => $event_format_checklist];

        }else{

            $event_format_checklist = EventFormat::all();

            return ['event_format_checklist' => $event_format_checklist];
        
        }

        
    }

    public function getCheckEventFormat(Request $request)
    {
        $check_format = json_decode($request->check_format);
        // $format_list = json_decode($request->format_list);
        // $check_format_list = json_decode($request->check_format_list);
        
        $event_format = EventFormat::whereIn('id', $check_format)->get();

        $event_format_checklist = EventFormat::whereNotIn('id', $check_format)->get();
        // $test = 'Hello!';

        return ['event_format' => $event_format, 'event_format_checklist' => $event_format_checklist];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event_format = EventFormat::find($id);
        if($event_category) {
            $event_format->delete();
            return redirect()->route('admin.event_format')->with(["status" => true]);
        }else{
            return redirect()->route('admin.event_format')->with(["status" => false]);
        }
    }
}
