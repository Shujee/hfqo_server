<?php

namespace App\Http\Controllers;

use App\Exam;
use Illuminate\Http\Request;
use App\Http\Resources\Exam as ExamResource;
use App\Http\Resources\Access as AccessResource;
use App\Http\Resources\NewlyCreatedExam;
use App\Notifications\ExamUploaded;
use Illuminate\Support\Facades\Log;
use App\Notifications\ResultUploaded;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

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

    public function view(\App\Exam $exam)
    {
        return new ExamResource($exam);
    }

    public function names()
    {
        return \App\Exam::selectRaw("CONCAT(`number`, ' (', `name`, ')') as name, id")->get();
    }

    public function number_exists($number) 
    {
        $Exists = \App\Exam::where('number', $number)->exists();
        return $Exists? "true" : "false";
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
        Log::alert("{$request->user()->name}  (id={$request->user()->id}) tried to upload a new master file.");

        $request->validate(
            [
                'number' => 'required|unique:exams',
                'name' => 'required',
                'qa_count' => 'required',
                'xps_content' => 'file',
                'xml_content' => 'file',
                'qas' => 'required',
                'origfilename' => 'required',
            ],
            [
                'number.required' => 'Exam number must be supplied.',
                'number.unique' => 'A master file with this number already exists on the server.',
                'name.required' => 'Exam name must be supplied.',
                'qa_count.required' => 'qa_count is required',
                'xps_content.file' => 'XPS flie must be supplied',
                'xml_content.file' => 'XML flie must be supplied',
                'qas.required' => 'QAs must be supplied',
                'origfilename.required' => 'Original File Name must be supplied',
            ]
        );

        $Exam = new \App\Exam;

        $Exam->uploader_id = $request->user()->id;

        $Exam->is_expired = false;
        $Exam->number = $request['number'];
        $Exam->name = $request['name'];
        $Exam->qa_count = $request['qa_count'];
        $Exam->remarks = 'NEW';
        $Exam->origfilename = $request['origfilename'];

        $Exam->xps_file_name = $request->file('xps_content')->store('xps');
        $Exam->xml_file_name = $request->file('xml_content')->store('xml');

        $Exam->save();

        $qas = json_decode($request['qas'], true);

        //insert all result rows in UploadRow table
        foreach ($qas as $qa) {
            $Q = \App\QA::make($qa);
            $Q['exam_id'] = $Exam->id;
            $Q->save();
        }

        try {
            (new SlackAgent())->notify(new ExamUploaded($Exam, true));
        }
        catch(Exception $e) {
            Log::alert("Slack notification failed [EXAM UPLOADED]. {$e->getMessage()}, User: {$request->user()->name}, Exam: {$Exam->name}");
        }

        return new NewlyCreatedExam($Exam);
    }

    /**
     * Updates the XPS and XML files of existing exam.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_files(Request $request, Exam $exam)
    {
        if ($exam->is_expired) {
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
                'remarks' => 'required',
                'origfilename' => 'required',
            ],
            [
                'qa_count.required' => 'qa_count is required',
                'remarks.required' => 'remarks field is required',
                'xps_content.file' => 'XPS flie must be supplied',
                'xml_content.file' => 'XML flie must be supplied',
                'qas.required' => 'QAs must be supplied',
                'origfilename.required' => 'Original File Name must be supplied',
            ]
        );

        if ($exam->uploader_id == $request->user()->id) {
            $exam->qa_count = $request['qa_count'];
            $exam->remarks = $request['remarks'];
            $exam->origfilename = $request['origfilename'];

            $exam->xps_file_name = $request->file('xps_content')->store('xps');
            $exam->xml_file_name = $request->file('xml_content')->store('xml');

            $exam->save();

            $qas = json_decode($request['qas'], true);

            //delete old QAs of this exam
            $exam->QAs()->delete();

            //insert new QAs
            foreach ($qas as $qa) {
                $qa['exam_id'] = $exam->id;
                \App\QA::create($qa);    
            }

            try {
                (new SlackAgent())->notify(new ExamUploaded($exam, false));
            }
            catch(Exception $e) {
                Log::alert("Slack notification failed [EXAM FILES UPDATED]. {$e->getMessage()}. User: {$request->user()->name}, Exam: {$exam->name}");
            }

            

            return "true";

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

        $IP = $this->getIp();
        if($IP == null){
            Log::debug('Client IP could not be resolved.');
            return response()->json([
                'error' => 'An internal error occurred. Contact administrator for more help.'
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
        else {

            //For admins, we'll create an Access row on the fly if one doesn't exist already for the specified exam
            if($request->user()->isAdmin()) {
                $MyAccess = $exam->GetFirstValidAccess($request->user()->id);
                
                if($MyAccess == null) {
                    $MyAccess = new \App\Access;
                    
                    $MyAccess->user_id = $request->user()->id;
                    $MyAccess->exam_id = $exam->id;
                    $MyAccess->start = Carbon::now();
                    $MyAccess->end = Carbon::now()->addDays(7);

                    $MyAccess->save();
                }
            }
            else {
                //Locate the Access row for current User and Exam
                $MyAccess = $exam->GetFirstValidAccess($request->user()->id);
            }

            if($MyAccess == null){
                return response()->json([
                    'error' => 'You do not have access to the specified master file.'
                ], 422);    
            }
            else {
                //record upload activity
                $UL = new \App\Upload;
                $UL->access_id = $MyAccess->id;
                $UL->ip = $IP;

                $Loc = $this->ip_2_city_country($IP);
                $UL->city = $Loc['city'];
                $UL->country = $Loc['country'];

                $UL->machine_name = $request['machine_name'];
                $UL->save();

                $result = json_decode($request['result'], true);

                //insert all result rows in UploadRow table
                foreach ($result as $r) {
                    $r['upload_id'] = $UL->id;
                    
                    if($r['a2'] == '')
                        $r['a2'] = null;

                    if($r['a3'] == '')
                        $r['a3'] = null;

                    \App\UploadRow::create($r);    
                }

                try {
                    (new SlackAgent())->notify(new ResultUploaded($UL));
                }
                catch(Exception $e) {
                    Log::alert("Slack notification failed [RESULT UPLOADED]. {$e->getMessage()}. User: {$request->user()->name}, Exam: {$exam->name}");
                }               

                return response()->json('success', 201);
            }
        }
    }

    public function hfqreport(Request $request)
    {       
       $Q = $this->hfqreport_query($request);
        return $Q->get();
    }

    public function hfqreportpdf(Request $request)
    {
        $Q = $this->hfqreport_query($request);

        $data = $Q->get();
        $columns = array('Index', 'Question', 'Answer', 'Frequency');
    
        $callback = function() use ($data, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
    
            foreach($data as $r) {
                fputcsv($file, array($r->index, $r->question, $r->answer, $r->freq));
            }

            fclose($file);
        };

        return response()->streamDownload($callback, 'hfqreport.csv');
    }

    private function hfqreport_query(Request $request)
    {
        $request->validate([
            'exam' => 'required|exists:exams,id',
            'start' => 'nullable|date',
            'end' => 'nullable|date',
            'frequency' => 'required|in:0,1,2,3'
        ],
        [
            'exam.required' => 'Master File must be selected.',
            'exam.exists' => 'Selected Master File does not exist on the server.',
            'start.date' => 'Start Date format is not correct.',
            'end.date' => 'End Date format is not correct.',
            'frequency.required' => 'Select a Frequency value from the dropdown.',
            'frequency.in' => 'Frequency value must be 0, 1, 2 or 3.',
        ]);

        $Q = \App\UploadRow 
        ::join('uploads', 'uploadrows.upload_id', '=', 'uploads.id')
        ->join('accesses', 'uploads.access_id', '=', 'accesses.id')
        ->join('qas', function($join) { 
            $join->on('uploadrows.a1', '=', 'qas.index')->
                 orOn('uploadrows.a2', '=', 'qas.index')->
                 orOn('uploadrows.a3', '=', 'qas.index');
        });
 
        $Q = $Q->where('accesses.exam_id', $request['exam'])
                ->whereRaw('qas.exam_id = accesses.exam_id'); //this condition should actually go into the join call above, but i can't find a way to group together the conditions that are already there

        if($request->filled('start')) {
            $Q = $Q->where('uploads.created_at', '>=', $request['start']);
        }
        
        if($request->filled('end')) {
            //End date is inclusive, so we'll acccept anything that is less than next day.
            $end = new \Carbon\Carbon($request->end);
            $end = $end->addDays(1);

            $Q = $Q->where('uploads.created_at', '<', $end);
        }

        if($request->filled('location')) {
            $Q = $Q->where('uploads.city', $request['location']['city']);
            $Q = $Q->where('uploads.country', $request['location']['country']);
        }

        $Q = $Q->groupBy('qas.index', 'qas.question', 'qas.answer');

        if($request->filled('frequency') && $request['frequency'] !== 0)
        {
            //For 3, we'll return everything >= 3. For 1 or 2, we'll perform exact match only.
            if($request['frequency'] == 3)
                $Q = $Q->havingRaw('freq >= ?', [ $request['frequency'] ]);
            else
                $Q = $Q->havingRaw('freq = ?', [ $request['frequency'] ]);
        }

        $Q = $Q->orderBy('freq', 'DESC')
                ->selectRaw('qas.index as `index`, qas.question, qas.answer, COUNT(*) as freq');

        return $Q;
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
