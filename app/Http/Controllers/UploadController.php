<?php

namespace App\Http\Controllers;

use App\Upload;
use Illuminate\Http\Request;
use App\Http\Resources\Upload as UploadResource;

class UploadController extends Controller
{
    use RequestIP;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->user()->trashed()) {
            return response()->json([
                'error' => 'You are not allowed to perform this operation.'
            ], 422);
        }

        $request->validate([
            'machine_name' => 'required',
            'exam_id' => 'required|integer|exists:exams,id',
            'result' => 'required'
        ],
        [
            'machine_name.required' => 'Machine name must be supplied.',
            'exam_id.required' => 'exam_id is required',
            'exam_id.integer' => 'exam_id is not correct',
            'exam_id.exists' => 'exam_id is not correct',
            'result' => 'result is required'
        ]);

        if($exam->trashed()){
            return response()->json([
                'error' => 'The specified master file has been deleted.'
            ], 422);
        }
        else if($exam->is_expired){
            return response()->json([
                'error' => 'The specified master file has expired.'
            ], 422);
        }
        else {
            //Locate the Access row for current User and Exam
            $MyAccess = $exam->GetFirstValidAccess($request->user(), $exam->id);

            if($MyAccess == null){
                return response()->json([
                    'error' => 'You do not have access to the specified master file.'
                ], 422);    
            }
            else {
                //record download activity
                $UL = new \App\Upload;
                $UL->access_id = $MyAccess->id;
                $UL->ip = getIp();
                $UL->machine_name = $request['machine_name'];
                $UL->save();

                $result = json_decode($request['result']);

                foreach ($result as $i => $value) {
                    $ULRow = new \App\UploadRow;
                    
                    $ULRow->upload_id = $UL->id;

                    $ULRow->q = $value['q'];
                    $ULRow->a1 = $value['a1'];
                    $ULRow->a2 = $value['a2'];
                    $ULRow->a3 = $value['a3'];

                    $ULRow->save();
                }

                return response()->json('success', 201);
            }
        }
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
}
