<?php

namespace App\Http\Controllers;

use App\Exam;
use Illuminate\Http\Request;
use App\Http\Resources\Exam as ExamResource;
use App\Http\Resources\Access as AccessResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

require_once __DIR__ .'/RequestIPTrait.php';

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
            'xps_content' => 'file',
            'xml_content' => 'file',
        ],
        [
            'name.required' => 'Name of the master file must be supplied.',
            'name.unique' => 'A master file with this name already exists on the server.',
            'qa_count.required' => 'qa_count is required',
            'xps_content.file' => 'XPS flie must be supplied',
            'xml_content.file' => 'XML flie must be supplied',
        ]);

        //record download activity
        $Exam = new \App\Exam();

        $Exam->is_expired = false;
        $Exam->name = $request['name'];
        $Exam->qa_count = $request['qa_count'];

        $Exam->xps_file_name = $request->file('xps_file_name')->store('xps');
        $Exam->xml_file_name = $request->file('xml_file_name')->store('xml');

        $Exam->save();
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
        if(!$request->filled('machine_name')) {
            return response()->json([
                'error' => 'Machine name must be supplied.'
            ], 422);
        }

        $IP = $this->getIp();
        if($IP == null){
            Log::debug('Client IP could not be resolved.');
            return response()->json([
                'error' => 'An internal error occurred. Contact administrator for more help.'
            ], 422);
        }

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
            $MyAccess = $exam->GetFirstValidAccess($request->user()->id);

            if($MyAccess == null){
                return response()->json([
                    'error' => 'You do not have access to the specified master file.'
                ], 422);    
            }
            else {
                //record download activity
                $DL = new \App\Download;
                $DL->access_id = $MyAccess->id;
                $DL->ip = $IP;
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
