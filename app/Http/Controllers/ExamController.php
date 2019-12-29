<?php

namespace App\Http\Controllers;

use App\Exam;
use Illuminate\Http\Request;
use App\Http\Resources\Exam as ExamResource;
use App\Http\Resources\Access as AccessResource;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
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
        if(!$request->user()->isUploader()){
            Log::alert("{$request->user()->name}  (id={$request->user()->id}) tried to upload a new master file.");

            return response()->json([
                'error' => 'Not allowed.'
            ], 403);
        }
        
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

        $Exam->uploader_id = $request->user()->id;

        $Exam->is_expired = false;
        $Exam->name = $request['name'];
        $Exam->qa_count = $request['qa_count'];

        $Exam->xps_file_name = $request->file('xps_file_name')->store('xps');
        $Exam->xml_file_name = $request->file('xml_file_name')->store('xml');

        $Exam->save();
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
