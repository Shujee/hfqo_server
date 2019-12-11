<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Access;
use Carbon\Carbon;

class AccessController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Access = new Access();

        $Access->user_id = $request['user_id'];
        $Access->exam_id = $request['exam_id'];
        $Access->start = $request['start'];
        $Access->end = $request['end'];

        $Access->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \\App\Access  $access
     * @return \Illuminate\Http\Response
     */
    public function show(Access $access)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \\App\Access  $access
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Access $access)
    {
        $access->start = $request['start'];
        $access->end = $request['end'];
        $access->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \\App\Access  $access
     * @return \Illuminate\Http\Response
     */
    public function destroy(Access $access)
    {
        $access->delete();
    }

    /**
     * Adds, updates or deletes accesses in the database.
     *
     * @param  \\App\Access  $access
     * @return \Illuminate\Http\Response
     */
    public function update_bulk(Request $request)
    {
        $Accesses = $request->json()->all();

        foreach ($Accesses as $Acc) {

            if ($Acc['deleted']) {
                $Access = Access::find($Acc['id']);

                if ($Access != null) {
                    $Access->delete();
                }
            }  
            else if ($Acc['added']) {
                $Access = new Access();

                $Access->user_id = $Acc['user_id'];
                $Access->exam_id = $Acc['exam_id'];
                $Access->start = Carbon::parse($Acc['start']);
                $Access->end = Carbon::parse($Acc['end']);

                $Access->save();
            }
            else /*all other rows will be treated as updated*/{
                $Access = Access::find($Acc['id']);

                if ($Access != null) {
                    $Access->start = Carbon::parse($Acc['start']);
                    $Access->end = Carbon::parse($Acc['end']);
                    $Access->save();
                }
            }
        }
    }
}
