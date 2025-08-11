<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ProgramsEducation;
use Illuminate\Support\Facades\DB;

class ProgramsEduController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = DB::table('programs_education')
            ->select([
                'programs_education.id as id',
                'organizations.name_filter as org',
                'organizations.name_short as org_name_short',
                'organizations.id as org_id',
                'organizations.logo as logo',
                'programs_education.date as date_init',
                DB::raw('DATE_FORMAT(programs_education.date, "%d.%m.%Y") as date'),
                'programs_education.price as price',
                'form_education.id as form_edu_id',
                'form_education.name as form_edu',
                'programs_education.duration as duration',
                'programs_education.extension as extension',
                'programs.id as program_id',
                'programs.name as program',
                'cities.id as city_id',
                'cities.name as city',
                'type_programs.id as type_program_id'
            ])
            ->join('programs', 'programs.id', '=', 'programs_education.program_id')
            ->join('organizations', 'organizations.id', '=', 'programs_education.organization_id')
            ->join('form_education', 'form_education.id', '=', 'programs_education.form_education_id')
            ->join('organization_city_joins', 'organizations.id', '=', 'organization_city_joins.organization_id')
            ->join('cities', 'cities.id', '=', 'organization_city_joins.city_id')
            ->join('program_type_program_joins', 'programs.id', '=', 'program_type_program_joins.program_id')
            ->join('type_programs', 'type_programs.id', '=', 'program_type_program_joins.type_program_id')
            ->orderBy('date_init', 'desc')
            ->get();

        return $programs;
    }

    public function all()
    {
        date_default_timezone_set("Europe/Moscow");
        $curr_date = date('Y-m-d');

        $programs = DB::table('programs_education')
            ->select([
                'programs_education.id as id',
                'organizations.name_filter as org',
                'organizations.name_short as org_name_short',
                'organizations.id as org_id',
                'organizations.logo as logo',
                'organizations.hidden_more as hidden_more',
                'programs_education.date as date_init',
                DB::raw('DATE_FORMAT(programs_education.date, "%d.%m.%Y") as date'),
                'programs_education.price as price',
                'programs_education.price_exam as price_exam',
                'form_education.id as form_edu_id',
                'form_education.name as form_edu',
                'programs_education.duration as duration',
                'programs_education.extension as extension',
                'programs.id as program_id',
                'programs.name as program',
                'cities.id as city_id',
                'cities.name as city',
                'type_programs.id as type_program_id',
                'type_programs.price_exam as tp_price_exam',
                'programs_groups.id as group_id',
                'programs_groups.name as group_name'
            ])
            ->join('programs', 'programs.id', '=', 'programs_education.program_id')
            ->join('organizations', 'organizations.id', '=', 'programs_education.organization_id')
            ->join('form_education', 'form_education.id', '=', 'programs_education.form_education_id')
            ->join('organization_city_joins', 'organizations.id', '=', 'organization_city_joins.organization_id')
            ->join('cities', 'cities.id', '=', 'organization_city_joins.city_id')
            ->join('program_type_program_joins', 'programs.id', '=', 'program_type_program_joins.program_id')
            ->join('type_programs', 'type_programs.id', '=', 'program_type_program_joins.type_program_id')
            ->leftJoin('programs_groups_joins', 'programs.id', '=', 'programs_groups_joins.program_id')
            ->leftJoin('programs_groups', 'programs_groups_joins.programs_group_id', '=', 'programs_groups.id')
            ->where('organizations.hidden_program', NULL)
            ->where('programs_education.date', '>=', $curr_date)
            ->orderBy('date_init', 'asc')
            ->get();

        return $programs;
    }

    public function getProgramsEduApp($type_program_id)
    {
        $programs = DB::table('programs_education')
            ->select([
                'programs_education.id as id',
                'programs.name as program_name',
                'organizations.name as org_name',
                DB::raw('DATE_FORMAT(programs_education.date, "%d.%m.%Y") as date'),
                DB::raw('COUNT(applications.id) as count_app')
            ])
            ->join('program_type_program_joins', 'program_type_program_joins.program_id', '=', 'programs_education.program_id')
            ->join('type_programs', 'type_programs.id', '=', 'program_type_program_joins.type_program_id')
            ->join('program_apps', 'program_apps.programs_education_id', '=', 'programs_education.id')
            ->join('applications', 'applications.id', '=', 'program_apps.application_id')
            ->join('programs', 'programs.id', '=', 'programs_education.program_id')
            ->join('organizations', 'organizations.id', '=', 'programs_education.organization_id')
            ->where('type_programs.id', $type_program_id)
            ->get();

        return $programs;
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
            'organization' => 'required|exists:organizations,id',
            'program' => 'required|exists:programs,id',
            'date' => 'required|date',
            'price' => 'required|numeric|min:0',
            // 'form_education' => 'required|array',
            // 'form_education.*' => 'exists:form_education,id',
            'form_education' => 'exists:form_education,id',
        ]);

        $price_exam = NULL;
        if(isset($request->price_exam) && ($request->price_exam == 1 || $request->price_exam == '1')) $price_exam = 1;

        $prog_edu = ProgramsEducation::create([
            'organization_id' => $validated['organization'],
            'program_id' => $validated['program'],
            'form_education_id' => $validated['form_education'],
            'date' => $validated['date'],
            'price' => $validated['price'],
            'price_exam' => $price_exam,
            'duration' => $request->duration,
            'extension' => $request->extension,
        ]);

        return response()->json(['programs_edu' => $prog_edu]);
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
        return ProgramsEducation::findOrFail($id);
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
                'organization' => 'required|exists:organizations,id',
                'program' => 'required|exists:programs,id',
                'date' => 'required|date',
                'price' => 'required|numeric|min:0',
                'form_education' => 'exists:form_education,id',
            ]);

            $price_exam = NULL;
            if(isset($request->price_exam) && ($request->price_exam == 1 || $request->price_exam == '1')) $price_exam = 1;

            $program = ProgramsEducation::find($id);
            $program->organization_id = $validated['organization'];
            $program->program_id = $validated['program'];
            $program->date = $validated['date'];
            $program->price = $validated['price'];
            $program->price_exam = $price_exam;
            $program->form_education_id = $validated['form_education'];
            $program->duration = $request->duration;
            $program->extension = $request->extension;

            if($program->isDirty()){
                $program->save();
            }

            return response()->json(['programs_edu' => $program]);
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
        $program = ProgramsEducation::find($id);
        if($program) {
            $program->delete();
            return ["status" => true];
        }else{
            return ["status" => false];
        }
    }
}
