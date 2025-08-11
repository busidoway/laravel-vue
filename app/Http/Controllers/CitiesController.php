<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class CitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return City::all();
    }

    public function getCitiesFilter($cat)
    {
        $cities = DB::table('cities')
            ->select('cities.id', 'cities.name', DB::raw('COUNT(cities.id) as cities_count'))
            ->join('organization_city_joins', 'cities.id', '=', 'organization_city_joins.city_id')
            ->join('programs_education', 'organization_city_joins.organization_id', '=', 'programs_education.organization_id')
            ->joinSub(function ($query) use ($cat) {
                $query->select('programs.id')
                    ->from('programs')
                    ->join('program_type_program_joins', 'programs.id', '=', 'program_type_program_joins.program_id')
                    ->where('program_type_program_joins.type_program_id', $cat);
            }, 'filtered_programs', function ($join) {
                $join->on('programs_education.program_id', '=', 'filtered_programs.id');
            })
            ->groupBy('cities.id', 'cities.name')
            ->orderBy('cities.name')
            ->get();

        return $cities;
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
            'name' => 'required|string|max:255',
        ]);

        $cities = City::create([
            'name' => $validated['name']
        ]);

        return $cities;
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
        return City::findOrFail($id);
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
        if($id){
            // Валидация входящих данных
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $cities = City::find($id);
            $cities->name = $validated['name'];

            if($cities->isDirty()){
                $cities->save();
            }

            return $cities;
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
        $cities = City::find($id);
        if($cities) {
            $cities->delete();
            return ["status" => true];
        }else{
            return ["status" => false];
        }
    }
}
