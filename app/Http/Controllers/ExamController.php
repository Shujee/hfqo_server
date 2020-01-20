<?php

namespace App\Http\Controllers;

use App\Exam;
use Illuminate\Http\Request;
use App\Http\Resources\Exam as ExamResource;
use App\Http\Resources\Access as AccessResource;
use Illuminate\Support\Facades\Log;

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

    public function names()
    {
        return \App\Exam::select('name', 'id')->get();
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
        if (!$request->user()->isUploader()) {
            Log::alert("{$request->user()->name}  (id={$request->user()->id}) tried to upload a new master file.");

            return response()->json([
                'error' => 'Not allowed.'
            ], 403);
        }

        $request->validate(
            [
                'name' => 'required|unique:exams',
                'qa_count' => 'required',
                'xps_content' => 'file',
                'xml_content' => 'file',
                'qas' => 'required',
            ],
            [
                'name.required' => 'Name of the master file must be supplied.',
                'name.unique' => 'A master file with this name already exists on the server.',
                'qa_count.required' => 'qa_count is required',
                'xps_content.file' => 'XPS flie must be supplied',
                'xml_content.file' => 'XML flie must be supplied',
                'qas.required' => 'QAs must be supplied',
            ]
        );

        $Exam = new \App\Exam;

        $Exam->uploader_id = $request->user()->id;

        $Exam->is_expired = false;
        $Exam->name = $request['name'];
        $Exam->qa_count = $request['qa_count'];

        $Exam->xps_file_name = $request->file('xps_content')->store('xps');
        $Exam->xml_file_name = $request->file('xml_content')->store('xml');

        $Exam->save();

        //todo: fill QAs
        //$exam->xml_file_name = $request['qas'];
    }

    /**
     * Updates the XPS and XML files of existing exam.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_files(Request $request, Exam $exam)
    {
        if (!$request->user()->isUploader()) {
            Log::alert("{$request->user()->name} (id={$request->user()->id}) tried to update {$exam->name} (id={$exam->id}).");

            return response()->json([
                'error' => 'Not allowed.'
            ], 403);
        } else if ($exam->is_expired) {
            return response()->json([
                'error' => 'Specified master file has expired.'
            ], 403);
        }

        $request->validate(
            [
                'qa_count' => 'required',
                'xps_content' => 'file',
                'xml_content' => 'file',
                'qas' => 'required',
            ],
            [
                'qa_count.required' => 'qa_count is required',
                'xps_content.file' => 'XPS flie must be supplied',
                'xml_content.file' => 'XML flie must be supplied',
                'qas.required' => 'QAs must be supplied',
            ]
        );

        if ($exam->uploader_id == $request->user()->id) {
            $exam->qa_count = $request['qa_count'];

            $exam->xps_file_name = $request->file('xps_content')->store('xps');
            $exam->xml_file_name = $request->file('xml_content')->store('xml');

            $exam->save();

            $qas = json_decode($request['qas'], true);

            //insert all result rows in UploadRow table
            foreach ($qas as $qa) {
                $r['exam_id'] = $exam->id;
                \App\QA::create($qa);    
            }
        } else {
            return response()->json([
                'error' => 'Not allowed.'
            ], 403);
        }
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload_result(Request $request, Exam $exam)
    {
        if($request->user()->trashed()) {
            return response()->json([
                'error' => 'You are not allowed to perform this operation.'
            ], 422);
        }

        $request->validate([
            'machine_name' => 'required',
            'result' => 'required'
        ],
        [
            'machine_name.required' => 'Machine name must be supplied.',
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
            $MyAccess = $exam->GetFirstValidAccess($request->user()->id);

            if($MyAccess == null){
                return response()->json([
                    'error' => 'You do not have access to the specified master file.'
                ], 422);    
            }
            else {
                //record upload activity
                $UL = new \App\Upload;
                $UL->access_id = $MyAccess->id;
                $UL->ip = $this->getIp();
                $UL->city = file_get_contents("https://ipapi.co/{$request->ip()}/city/");
                $UL->country = file_get_contents("https://ipapi.co/{$request->ip()}/country/");
                $UL->machine_name = $request['machine_name'];
                $UL->save();

                $result = json_decode($request['result'], true);

                //insert all result rows in UploadRow table
                foreach ($result as $r) {
                    $r['upload_id'] = $UL->id;
                    \App\UploadRow::create($r);    
                }

                return response()->json('success', 201);
            }
        }
    }

    public function hfqreport(Request $request)
    {
        $Q = \App\UploadRow 
        ::join('uploads', 'uploadrows.upload_id', '=', 'uploads.id')
        ->join('accesses', 'uploads.access_id', '=', 'accesses.id')
        ->join('exams', 'accesses.exam_id', '=', 'exams.id');

        if($request->filled('start')) {
            $Q = $Q->where('uploads.created_at', '>=', $request['start']);
        }
        
        if($request->filled('end')) {
            $Q = $Q->where('uploads.created_at', '<=', $request['end']);
        }

        if($request->filled('location')) {
            $Q = $Q->where('uploads.city', $request['location']['city']);
            $Q = $Q->where('uploads.country', $request['location']['country']);
        }

        $Q = $Q->groupBy('exams.id', 'uploadrows.a1', 'uploadrows.a2', 'uploadrows.a3')
            ->havingRaw('COUNT(*) > ?', [ $request['frequency'] ])
            ->orderBy('freq', 'DESC')
            ->selectRaw('exams.id as exam, uploadrows.a1, uploadrows.a2, uploadrows.a3, COUNT(*) as freq');

        return $Q->get();
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
}
