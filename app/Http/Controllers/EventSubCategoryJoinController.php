<?php

namespace App\Http\Controllers;

use App\Models\EventSubCategory;
use Illuminate\Http\Request;
use App\Models\EventSubCategoryEventJoin;
use App\Models\EventSubCategoryJoin;
use Illuminate\Support\Facades\DB;

class EventSubCategoryJoinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            '*.event_category_id' => 'required|integer',
            '*.event_sub_category_id' => 'required|integer',
        ]);

        $created = [];

        foreach ($validated as $item) {
            $exists = EventSubCategoryJoin::where('event_category_id', $item['event_category_id'])
                ->where('event_sub_category_id', $item['event_sub_category_id'])
                ->exists();

            if (!$exists) {
                $created[] = EventSubCategoryJoin::create([
                    'event_category_id' => $item['event_category_id'],
                    'event_sub_category_id' => $item['event_sub_category_id'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $created,
            'message' => 'Связь успешно создана',
        ], 201);
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
     * Возвращает список подкатегорий, связанных с указанной категорией.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $subcategories = EventSubCategoryJoin::where('event_category_id', $id)->get();

        return response()->json([
            'success' => true,
            'data' => $subcategories,
            'message' => 'Связанные подкатегории успешно получены',
        ]);
    }

    /**
     * Обновляет записи связанных категорий и подкатегорий
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            '*.event_sub_category_id' => 'required|integer',
        ]);

        $data = $request->all();
        $created = [];
        $sub_category_ids = [];

        DB::transaction(function () use ($data, $id, &$created, &$sub_category_ids) {
            foreach ($data as $item) {
                $sub_category_ids[] = $item['event_sub_category_id'];

                $exists = EventSubCategoryJoin::where('event_category_id', $id)
                    ->where('event_sub_category_id', $item['event_sub_category_id'])
                    ->exists();

                if (!$exists) {
                    $created[] = EventSubCategoryJoin::create([
                        'event_category_id' => $id,
                        'event_sub_category_id' => $item['event_sub_category_id'],
                    ]);
                }
            }

            if ($sub_category_ids) {
                EventSubCategoryJoin::where('event_category_id', $id)
                    ->whereNotIn('event_sub_category_id', $sub_category_ids)
                    ->delete();
            }
        });

        return response()->json([
            'success' => true,
            'data' => $created,
            'message' => 'Связь успешно обновлена',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
