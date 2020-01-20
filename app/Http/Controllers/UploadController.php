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
    public function locations(Upload $upload)
    {
        return \App\Upload::groupBy('city', 'country')->select('city', 'country')->get();
    }
}
