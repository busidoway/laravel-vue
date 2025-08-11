<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventCategory;
use Illuminate\Support\Facades\Validator;

class EventCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EventCategory::orderBy('id', 'desc')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('admin.pages.event_category_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                "title" => ["required"]
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->messages(),
            ], 200);
        }

        if(!empty($request->code)){
            $code = $request->code;
        }else{
            $code = translit_sef($request->title);
        }

        $event_category = EventCategory::create([
            "title" => $request->title,
            "code" => $code
        ]);

        return response()->json([
            'success' => true,
            'data' => $event_category,
            'message' => 'Данные успешно сохранены',
        ], 200);
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
        return EventCategory::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        if (!$id) {
            return response()->json([
                'error' => true,
                'message' => 'ID не передан',
            ], 400);
        }

        $validator = Validator::make(
            $request->all(),
            [
                "title" => ["required"]
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->messages(),
            ], 200);
        }

        $event_category = EventCategory::find($id);

        $event_category->title = $request->title;

        if (isset($request->code)) {
            $event_category->code = $request->code;
        } else {
            $event_category->code = translit_sef($request->title);
        }

        if ($event_category->isDirty()) {
            $event_category->save();
        }

        return response()->json([
            'success' => true,
            'data' => $event_category,
            'message' => 'Данные успешно обновлены',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        if (!$id) {
            return response()->json(['status' => 'error', 'message' => 'ID не передан'], 400);
        }

        $event_category = EventCategory::find($id);

        if (!$event_category) {
            return response()->json(['status' => 'error', 'message' => 'Запись не найдена'], 404);
        }

        $event_category->delete();

        return response()->json(['status' => 'success', 'message' => 'Запись успешно удалена']);
    }
}
