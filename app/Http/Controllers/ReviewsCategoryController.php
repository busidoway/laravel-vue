<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReviewsCategory;
use App\Models\ReviewsCategoryPerson;

class ReviewsCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ReviewsCategory::orderBy('id', 'desc')->get();
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
            'name' => 'required|string|max:255'
        ]);

        $reviews_category = ReviewsCategory::create([
            'name' => $validated['name'],
            'code' => translit_sef($validated['name'])
        ]);

        if ($request->person && $reviews_category) {
            $reviews_category_people = ReviewsCategoryPerson::create([
                'reviews_category_id' => $reviews_category->id,
                'person_id' => $request->person
            ]);
        }

        return $reviews_category;
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
        $reviews_category = ReviewsCategory::findOrFail($id);
        $reviews_category_people = ReviewsCategoryPerson::where('reviews_category_id', $reviews_category->id)->first();

        return ['reviews_category' => $reviews_category, 'reviews_category_people' => $reviews_category_people];
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
            // Валидация входящих данных
            $validated = $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $reviews_category = ReviewsCategory::find($id);
            $reviews_category->name = $validated['name'];
            $reviews_category->code = translit_sef($validated['name']);

            if ($request->person) {
                $reviews_category_people = ReviewsCategoryPerson::where('reviews_category_id', $reviews_category->id)->first();
                if ($reviews_category_people) {
                    $reviews_category_people->person_id = $request->person;
                    if ($reviews_category_people->isDirty()) {
                        $reviews_category_people->save();
                    }
                }
            }

            if ($reviews_category->isDirty()) {
                $reviews_category->save();
            }

            return $reviews_category;
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
        $reviews_category = ReviewsCategory::find($id);
        if ($reviews_category) {
            $reviews_category->delete();
            return ['status' => true];
        } else {
            return ['status' => false];
        }
    }
}
