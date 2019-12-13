<?php

namespace App\Http\Controllers;

use App\Exam;
use Illuminate\Http\Request;
use App\Http\Resources\Exam as ExamResource;
use App\Http\Resources\Access as AccessResource;

require_once 'RequestIPTrait.php';

class ExamController extends Controller
{
    use RequestIP;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Exams = Exam::all();
        return ExamResource::collection($Exams);
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
        $request->validate([
            'name' => 'required|unique:exams',
            'qa_count' => 'required',
            'xps_content' => 'required',
            'xml_content' => 'required',
            
        ],
        [
            'machine_name.required' => 'Machine name must be supplied.',
            'exam_id.required' => 'exam_id is required',
            'exam_id.integer' => 'exam_id is not correct',
            'exam_id.exists' => 'exam_id is not correct',
            'result' => 'result is required'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $exam)
    {
        $exam->fill($request->all());
        $exam->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();
    }

    /**
     * Returns all access rows of this Exam object
     *
     * @param  \App\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function accesses(Exam $exam)
    {
        $Accesses = $exam->Accesses;
        $res = AccessResource::collection($Accesses);

        return $res;
    }

    /**
     * Returns XPS and XML files of the specified exam if current user has been granted access to this exam.
     *
     * @param  \App\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, Exam $exam)
    {
        $request->validate([
            'machine_name' => 'required',
        ],
        [
            'machine_name.required' => 'Machine name must be supplied.',
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
                $DL = new \App\Download;
                $DL->access_id = $MyAccess->id;
                $DL->ip = getIp();
                $DL->machine_name = $request['machine_name'];
                $DL->save();

                $xps = Storage::disk('local')->get('xps', $exam->xps_file_name);
                $xml = Storage::disk('local')->get('xml', $exam->xml_file_name);
    
                return response()->json([
                    'xps'=> $xps,
                    'xml'=> $xml
                ]);    
            }
        }
    }
}
