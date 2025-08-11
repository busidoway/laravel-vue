<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoCategory;
use Validator;

class VideoCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $video_category = VideoCategory::orderBy('id', 'desc')->paginate(10);

        return view('admin.pages.video_category', ['video_category' => $video_category]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.video_category_create');
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
            return redirect()->back()->with(["status" => false, "errors" => $validator->messages()]);
        }

        if(isset($request->code)){
            $code = $request->code;
        }else{
            $code = translit_sef($request->title);
        }

        $video_category = VideoCategory::create([
            "title" => $request->title,
            "code" => $code
        ]);

        return redirect()->route('video_category.edit', $video_category->id)->with(['status' => true]);
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
        $video_category = VideoCategory::find($id);

        return view('admin.pages.video_category_edit', ['video_category' => $video_category]);
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

        $video_category = VideoCategory::find($id);

        $video_category->title = $request->title;

        if(isset($request->code)){
            $video_category->code = $request->code;
        }else{
            $video_category->code = translit_sef($request->title);
        }

        if($video_category->isDirty()){
            $video_category->save();
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
        $video_category = VideoCategory::find($id);
        if($video_category) {
            $video_category->delete();
            return redirect()->route('admin.video_category')->with(["status" => true]);
        }else{
            return redirect()->route('admin.video_category')->with(["status" => false]);
        }
    }
}
