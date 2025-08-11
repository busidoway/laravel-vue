<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use Validator;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slider = Slider::orderBy('sort', 'asc')->paginate(10);

        return view('admin.pages.slider', ['slider' => $slider]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.slider_create');
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

        $img = $request->file('image');

        if ($img) {
            $path_img = $img->store('images/photo', 'public');
        } else {
            $path_img = null;
        }

        if (isset($request->date))
            $date = date("Y-m-d", strtotime($request->date));
        else
            $date = NULL;

        if (isset($request->date_end))
            $date_end = date("Y-m-d", strtotime($request->date_end));
        else
            $date_end = NULL;

        $slider_table = Slider::all();
        $max = $slider_table->max('sort') + 1;

        $img_full = 0;
        if (isset($request->check_img_full)) {
            $img_full = 1;
        }

        $slider = Slider::create([
            "title" => $request->title,
            "text1" => $request->text1,
            "text2" => $request->text2,
            "text3" => $request->text3,
            "image" => $path_img,
            "img_full" => $img_full,
            "url" => $request->url,
            "date" => $date,
            "date_end" => $date_end,
            "sort" => $max
        ]);

        return redirect()->route('slider.edit', $slider->id)->with(['status' => true]);
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
        $slider = Slider::find($id);

        return view('admin.pages.slider_edit', ['slider' => $slider]);
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

        $slider = Slider::find($id);

        if (isset($request->date))
            $date = date("Y-m-d", strtotime($request->date));
        else
            $date = NULL;

        if (isset($request->date_end))
            $date_end = date("Y-m-d", strtotime($request->date_end));
        else
            $date_end = NULL;

        $slider->title = $request->title;
        $slider->text1 = $request->text1;
        $slider->text2 = $request->text2;
        $slider->text3 = $request->text3;
        $slider->url = $request->url;
        $slider->date = $date;
        $slider->date_end = $date_end;

        $img = $request->file('image');

        if ($img) {
            $slider->image = $img->store('images/photo', 'public');
        }

        if (isset($request->check_img_full)) {
            $slider->img_full = 1;
        }

        if ($slider->isDirty()) {
            $slider->save();
        }

        return redirect()->back()->with(['status' => true]);
    }

    public function sortUp($id)
    {
        $slider = Slider::find($id);

        $this_sort = $slider->sort;

        if($this_sort != 1 && $this_sort != 0){
            $new_sort = $this_sort - 1;

            $slider_up = Slider::where('sort', $new_sort)->increment('sort', 1);

            $slider->decrement('sort', 1);
        }

        return redirect()->route('admin.slider')->with(["status" => true]);
    }

    public function sortDown($id)
    {
        $slider = Slider::find($id);

        $slider_table = Slider::all();

        $max = $slider_table->max('sort');

        // dd($max);

        $this_sort = $slider->sort;

        if($this_sort != $max && $this_sort != 0){
            $new_sort = $this_sort + 1;

            $slider_down = Slider::where('sort', $new_sort)->decrement('sort', 1);

            $slider->increment('sort', 1);
        }

        return redirect()->route('admin.slider')->with(["status" => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::find($id);

        $this_sort = $slider->sort;

        $slider_table = Slider::all();
        $max = $slider_table->max('sort');

        if($this_sort != $max){
            $prev_slider = Slider::where('sort', '>', $this_sort)->decrement('sort', 1);
        }

        if($slider) {
            $slider->delete();
            return redirect()->route('admin.slider')->with(["status" => true]);
        }else{
            return redirect()->route('admin.slider')->with(["status" => false]);
        }
    }
}
