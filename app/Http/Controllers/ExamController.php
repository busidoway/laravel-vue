<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        date_default_timezone_set("Europe/Moscow");
        $curr_date = date('Y-m-d');

        $exams = Exam::select([
            'exams.id',
            'exams.name',
            'exams.date as date_init',
            DB::raw('DATE_FORMAT(exams.date, "%d.%m.%Y") as date'),
        ])
            ->where('exams.date', '>', $curr_date)
            ->orderBy('exams.date')
            ->get();

        $earliestDate = Exam::where('date', '>', $curr_date)->first();
        $latestDate = Exam::max('date');

        return response()->json([
            'exams' => $exams,
            'start_date' => $earliestDate,
            'end_date' => $latestDate,
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
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $exam = Exam::create([
            'name' => $validated['name'],
            'date' => $validated['date'],
        ]);

        return $exam;
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
        return Exam::findOrFail($id);
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
                'name' => 'required|string|max:255',
                'date' => 'required|date',
            ]);

            $exam = Exam::find($id);
            $exam->name = $validated['name'];
            $exam->date = $validated['date'];

            if($exam->isDirty()){
                $exam->save();
            }

            return $exam;
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
        $exam = Exam::find($id);
        if ($exam) {
            $exam->delete();
            return ["status" => true];
        } else {
            return ["status" => false];
        }
    }
}
