<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\ProgramTypeProgramJoin;
use Illuminate\Support\Facades\DB;
use App\Models\ProgramsGroupsJoin;

class ProgramsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Program::all();
    }

    public function getProgramsFilter($cat)
    {
        $programs = DB::table('programs')
            ->select(
                'programs.id as program_id',
                'programs.name as program_name',
                'programs_groups.id as group_id',
                'programs_groups.name as group_name',
                DB::raw('COUNT(programs_education.program_id) as program_count')
            )
            ->leftJoin('programs_education', 'programs.id', '=', 'programs_education.program_id')
            ->leftJoin('program_type_program_joins', 'programs.id', '=', 'program_type_program_joins.program_id')
            ->leftJoin('programs_groups_joins', 'programs.id', '=', 'programs_groups_joins.program_id')
            ->leftJoin('programs_groups', 'programs_groups_joins.programs_group_id', '=', 'programs_groups.id')
            ->where('program_type_program_joins.type_program_id', $cat)
            ->groupBy('programs.id', 'programs_groups.id')
            ->orderBy('programs.name')
            ->get();

        $groups = [];
        $ungroupedPrograms = [];

        foreach ($programs as $program) {
            if ($program->group_id) {
                if (!isset($groups[$program->group_id])) {
                    $groups[$program->group_id] = [
                        'id' => $program->group_id,
                        'name' => $program->group_name,
                        'programs' => [],
                    ];
                }
                $groups[$program->group_id]['programs'][] = [
                    'id' => $program->program_id,
                    'name' => $program->program_name,
                    'program_count' => $program->program_count,
                ];
            } else {
                $ungroupedPrograms[] = [
                    'id' => $program->program_id,
                    'name' => $program->program_name,
                    'program_count' => $program->program_count,
                ];
            }
        }

        return [
            'groups' => array_values($groups),
            'programs' => $ungroupedPrograms,
        ];
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
            'type_program' => 'required|exists:type_programs,id',
        ]);

        $program = Program::create([
            'name' => $validated['name'],
            'description' => $request->description
        ]);

        $prog_type_join = ProgramTypeProgramJoin::create([
            'program_id' => $program->id,
            'type_program_id' => $validated['type_program']
        ]);

        if ($request->programs_group) {
            $programs_groups_join = ProgramsGroupsJoin::create([
                'programs_group_id' => $request->programs_group,
                'program_id' => $program->id
            ]);
        }

        return $program;
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
        $program = Program::findOrFail($id);
        $program_join = ProgramTypeProgramJoin::where('program_id', $program->id)->first();
        $programs_groups_join = ProgramsGroupsJoin::where('program_id', $program->id)->first();

        return ['program' => $program, 'program_join' => $program_join, 'programs_groups_join' => $programs_groups_join];
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
                'type_program' => 'required|exists:type_programs,id',
            ]);

            $program = Program::find($id);
            $program->name = $validated['name'];
            $program->description = $request->description;

            $prog_type_join = ProgramTypeProgramJoin::where('program_id', $id)->first();
            if($prog_type_join){
                $prog_type_join->type_program_id = $validated['type_program'];
                if($prog_type_join->isDirty()){
                    $prog_type_join->save();
                }
            }

            if ($request->programs_group) {
                $programs_groups_join = ProgramsGroupsJoin::where('program_id', $id)->first();
                if ($programs_groups_join) {
                    $programs_groups_join->programs_group_id = $request->programs_group;
                    if ($programs_groups_join->isDirty()) {
                        $programs_groups_join->save();
                    }
                } else {
                    $programs_groups_join = ProgramsGroupsJoin::create([
                        'programs_group_id' => $request->programs_group,
                        'program_id' => $id
                    ]);
                }
            }

            if($program->isDirty()){
                $program->save();
            }

            return $program;
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
        $program = Program::find($id);
        if($program) {
            $program->delete();
            return ["status" => true];
        }else{
            return ["status" => false];
        }
    }
}
