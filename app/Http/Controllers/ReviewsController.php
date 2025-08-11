<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Review::all();
    }

    public function getAll()
    {
        $reviews = DB::table('reviews')
            ->select(
                        'reviews.name',
                        'reviews.position',
                        'reviews.text',
                        'reviews_categories.id as cat_id',
                        'reviews_categories.name as cat_name',
                    )
            ->join('reviews_categories', 'reviews_categories.id', '=', 'reviews.reviews_category_id')
            // ->get()
            // ->groupBy('cat_name')
            ->orderBy('reviews_categories.id', 'desc')
            ->paginate(10);

        // Получаем первую категорию на текущей странице
        $firstCategory = $reviews->isEmpty() ? null : $reviews->first()->cat_id;

        return view('pages.reviews.reviews', ['reviews' => $reviews, 'firstCategory' => $firstCategory]);

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
        // Валидация входящих данных
        $validated = $request->validate([
            'reviews_category' => 'required|exists:reviews_category,id',
            'name' => 'required|string|max:255',
        ]);

        $reviews = Review::create([
            'reviews_category_id' => $validated['reviews_category'],
            'name' => $validated['name'],
            'position' => $request->position,
            'text' => $request->text
        ]);

        return $reviews;
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
        //
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
        //
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
