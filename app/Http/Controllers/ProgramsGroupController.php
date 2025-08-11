<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgramsGroup;

class ProgramsGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProgramsGroup::all();
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
            'name' => 'required|string|max:255'
        ]);

        if(empty($request->code)) {
            $code = translit_sef($validated['name']);
        }else{
            $code = $request->code;
        }

        $programs_group = ProgramsGroup::create([
            'name' => $validated['name'],
            'code' => $code,
        ]);

        return $programs_group;
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
        $programs_group = ProgramsGroup::findOrFail($id);

        return $programs_group;
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
        if($id) {
            // Валидация входящих данных
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            if(empty($request->code)) {
                $code = translit_sef($validated['name']);
            }else{
                $code = $request->code;
            }

            $programs_group = ProgramsGroup::find($id);
            $programs_group->name = $validated['name'];
            $programs_group->code = $code;

            if($programs_group->isDirty()){
                $programs_group->save();
            }

            return $programs_group;
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
        $programs_group = ProgramsGroup::find($id);
        if($programs_group) {
            $programs_group->delete();
            return ["status" => true];
        }else{
            return ["status" => false];
        }
    }
}
