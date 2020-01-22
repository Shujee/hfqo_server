<?php

namespace App\Http\Controllers;

use App\Exam;
use App\Upload;
use Illuminate\Http\Request;
use App\Http\Resources\Upload as UploadResource;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Uploads = Upload::all();
        return UploadResource::collection($Uploads);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function show(Upload $Upload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Upload $upload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function destroy(Upload $upload)
    {
        //
    }

    /**
     * Returns unique locations (city/country) from the uploads table
     *
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function locations()
    {
        return \App\Upload::groupBy('city', 'country')->select('city', 'country')->get();
    }

    /**
     * Returns unique dates on which uploads were created during the specified period
     *
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function dates(Request $request, Exam $exam)
    {
        $Q = \App\Upload::
            join('accesses', 'uploads.access_id', '=', 'accesses.id')->
            join('exams', 'accesses.exam_id', '=', 'exams.id')->
            where('exams.id', $exam->id)->
            groupBy(['uploads.created_at'])->
            selectRaw('DATE(uploads.created_at) as Date')->
            pluck('Date');

        return $Q;
    }
}
