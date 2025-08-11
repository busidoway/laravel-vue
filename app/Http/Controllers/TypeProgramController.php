<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeProgram;

class TypeProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TypeProgram::all();
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

        if(empty($request->code)) {
            $code = translit_sef($validated['name']);
        }else{
            $code = $request->code;
        }

        $price_exam = NULL;
        if(isset($request->price_exam) && ($request->price_exam == 1 || $request->price_exam == '1')) $price_exam = 1;

        $typeProgram = TypeProgram::create([
            'name' => $validated['name'],
            'code' => $code,
            'url' => $request->url,
            'text' => $request->text,
            'price_exam' => $price_exam,
        ]);

        return $typeProgram;
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
        $typeProgram = TypeProgram::findOrFail($id);

        return $typeProgram;
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

            if(empty($request->code)) {
                $code = translit_sef($validated['name']);
            }else{
                $code = $request->code;
            }

            $price_exam = NULL;
            if(isset($request->price_exam) && ($request->price_exam == 1 || $request->price_exam == '1')) $price_exam = 1;

            $typeProgram = TypeProgram::find($id);
            $typeProgram->name = $validated['name'];
            $typeProgram->code = $code;
            $typeProgram->url = $request->url;
            $typeProgram->text = $request->text;
            $typeProgram->price_exam = $price_exam;

            if($typeProgram->isDirty()){
                $typeProgram->save();
            }

            return $typeProgram;
        }
    }

    public function getTypeProgramTitle($id)
    {
        $data = TypeProgram::select('name')->where('id', $id)->first();

        return ['type_program_name' => strip_tags($data->name)];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $typeProgram = TypeProgram::find($id);
        if($typeProgram) {
            $typeProgram->delete();
            return ["status" => true];
        }else{
            return ["status" => false];
        }
    }
}
