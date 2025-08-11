<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormEducation;
use Illuminate\Support\Facades\DB;

class FormEducationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FormEducation::all();
    }

    public function getFormEduFilter($cat)
    {
        $form_edu = DB::table('form_education')
            ->select('form_education.id', 'form_education.name', DB::raw('COUNT(form_education.id) as form_education_count'))
            ->join('programs_education', 'form_education.id', '=', 'programs_education.form_education_id')
            ->join('program_type_program_joins', 'programs_education.program_id', '=', 'program_type_program_joins.program_id')
            ->where('program_type_program_joins.type_program_id', $cat)
            ->groupBy('form_education.id')
            ->get();

        return $form_edu;
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

        $formEducation = FormEducation::create([
            'name' => $validated['name']
        ]);

        return $formEducation;
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
        return FormEducation::findOrFail($id);
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

            $formEducation = FormEducation::find($id);
            $formEducation->name = $validated['name'];

            if($formEducation->isDirty()){
                $formEducation->save();
            }

            return $formEducation;
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
        $formEducation = FormEducation::find($id);
        if($formEducation) {
            $formEducation->delete();
            return ["status" => true];
        }else{
            return ["status" => false];
        }
    }
}
