<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Access;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\RequestIP;
use App\Notifications\ExamDownloaded;
use App\Notifications\GenericException;
use Exception;

class AccessController extends Controller
{
    use RequestIP;

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

    /**
     * Returns XPS and XML files of the specified exam if current user has been granted access to this exam.
     *
     * @param  \App\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, Access $access)
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

        $exam = $access->Exam;

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

                try {

                    //record download activity
                    $DL = new \App\Download;
                    $DL->access_id = $MyAccess->id;
                    $DL->ip = $IP;

                    $Loc = $this->ip_2_city_country($IP);
                    $DL->city = $Loc['city'];
                    $DL->country = $Loc['country'];

                    $DL->machine_name = $request['machine_name'];
                    $DL->save();

                    if(Storage::disk('local')->exists($exam->xps_file_name) && Storage::disk('local')->exists($exam->xml_file_name))
                    {
                        $xps = Storage::disk('local')->get($exam->xps_file_name);
                        $xml = Storage::disk('local')->get($exam->xml_file_name);

                        $xps64 = base64_encode($xps);
                        $xml64 = base64_encode($xml);

                        try {
                            (new SlackAgent())->notify(new ExamDownloaded($DL));
                        } 
                        catch(Exception $e) {
                            Log::alert("Slack notification failed [EXAM DOWNLOADED]. {$e->getMessage()}. User: {$request->user()->name}, Exam: {$exam->name}");
                        }

                        return response()->json([
                            'id' => $exam->id,
                            'xps'=> $xps64,
                            'xml'=> $xml64,
                            'download_id' => $DL->id,
                            'number' => $exam->number,
                            'name' => $exam->name,
                        ]);
                    } 
                    else {
                        return response()->json([
                            'error' => 'Specified master file does not exist on the server. Please contact server administrator.'
                        ], 422);
                    }
                } catch (Exception $e) {
                    (new SlackAgent())->notify(new GenericException($IP, $request->user(), $e));

                    return response()->json([
                        'error' => 'A server-side error occurred while trying to download exam files.'
                    ], 500);
                }
            }
        }
    }
}
