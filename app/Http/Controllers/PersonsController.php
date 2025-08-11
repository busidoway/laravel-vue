<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use Validator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
// use Symfony\Component\HttpFoundation\File\UploadedFile;

class PersonsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $persons = DB::table('people')
                    ->select(DB::raw('id, last_name, name, middle_name, SUBSTRING(position, 1, 100) as position_short, LENGTH(position) as position_length'))
                    ->orderBy('id', 'desc')
                    ->paginate(10);

        return view('admin.pages.persons', ['persons' => $persons]);
    }

    public function getPersonsList()
    {
        $persons = Person::all();

        // return view('modules.video_filter', ['persons' => $persons]);
        return view('pages.events_video', ['persons' => $persons]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.person_create');
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
                "last_name" => ["required"]
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with(["status" => false, "errors" => $validator->messages()]);
        }

        $img = $request->file('image');

        if($img){
            $path_img = $img->store('images/photo', 'public');
        }else{
            $path_img = null;
        }

        $persons = Person::create([
            "last_name" => $request->last_name,
            "name" => $request->name,
            "middle_name" => $request->middle_name,
            "position" => $request->position,
            "img" => $path_img
        ]);

        return redirect()->route('persons.edit', $persons->id)->with(['status' => true]);
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
        $persons = Person::find($id);

        return view('admin.pages.person_edit', ['persons' => $persons]);
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
                "last_name" => ["required"]
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with(["status" => false, "errors" => $validator->messages()]);
        }

        $persons = Person::find($id);

        $persons->last_name = $request->last_name;
        $persons->name = $request->name;
        $persons->middle_name = $request->middle_name;
        $persons->position = $request->position;

        $img = $request->file('image');

        if($img){
            $persons->img = $img->store('images/photo', 'public');
        }

        if($persons->isDirty()){
            $persons->save();
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
        $persons = Person::find($id);
        if($persons) {
            $persons->delete();
            return redirect()->route('admin.persons')->with(["status" => true]);
        }else{
            return redirect()->route('admin.persons')->with(["status" => false]);
        }
    }
}
