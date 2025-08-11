<?php

namespace App\Http\Controllers;

use App\Models\EventSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return EventSubCategory[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return EventSubCategory::all();
    }

    public function getEventSubCategories(Request $request)
    {
        $ids = $request->input('ids', []);

        $sub_categories = EventSubCategory::whereNotIn('id', $ids)->orderBy('id')->get();
        $sub_categories_selected = EventSubCategory::whereIn('id', $ids)->orderBy('id')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'sub_categories' => $sub_categories,
                'sub_categories_selected' => $sub_categories_selected
            ],
            'message' => 'Подкатегории успешно получены',
        ]);
    }

    public function getDataEventSubCategories($id)
    {

        $sub_categories = DB::table('event_sub_categories')
                            ->select('event_sub_categories.id', 'event_sub_categories.title')
                            ->join('event_sub_category_joins', 'event_sub_categories.id', '=', 'event_sub_category_joins.event_sub_category_id')
                            ->where('event_sub_category_joins.event_category_id', $id)
                            ->get();

        return response()->json([
            'success' => true,
            'data' => $sub_categories,
            'message' => 'Подкатегории успешно получены',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
        ]);

        if (!empty($request->code)) {
            $code = translit_sef($request->code);
        } else {
            $code = translit_sef($validated['title']);
        }

        $subcategory = EventSubCategory::create([
            'title' => $validated['title'],
            'code' => $code
        ]);

        return $subcategory;
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
        $subcategory = EventSubCategory::findOrFail($id);
        return $subcategory;
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
        if ($id) {

            $validated = $request->validate([
                'title' => 'required|string',
            ]);

            if (!empty($request->code)) {
                $code = translit_sef($request->code);
            } else {
                $code = translit_sef($validated['title']);
            }

            $subcategory = EventSubCategory::find($id);
            $subcategory->title = $validated['title'];
            $subcategory->code = $code;

            if ($subcategory->isDirty()) {
                $subcategory->save();
            }

            return $subcategory;

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        if (!$id) {
            return response()->json(['status' => 'error', 'message' => 'ID не передан'], 400);
        }

        $subcategory = EventSubCategory::find($id);

        if (!$subcategory) {
            return response()->json(['status' => 'error', 'message' => 'Запись не найдена'], 404);
        }

        $subcategory->delete();

        return response()->json(['status' => 'success', 'message' => 'Запись успешно удалена']);
    }
}
