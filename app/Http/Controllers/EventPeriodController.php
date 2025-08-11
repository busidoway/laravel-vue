<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventPeriod;

class EventPeriodController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Return date_start and date_end from table 'event_periods'
     *
     * @param int $id
     * @param int $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEventPeriodDate(int $id, int $event_id): \Illuminate\Http\JsonResponse
    {
        $event_period = EventPeriod::select('id, date_start, date_end')
            ->where('id', $id)
            ->where('event_id', $event_id)
            ->first();

        return response()->json([
            'status' => true,
            'data' => $event_period
        ]);
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
