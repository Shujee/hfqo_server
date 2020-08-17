<?php

namespace App\Http\Controllers;

use App\Download;
use Illuminate\Http\Request;
use App\Http\Resources\Download as DownloadResource;
use App\Notifications\GenericException;
use Exception;
use Intervention\Image\Facades\Image;

class DownloadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Downloads = Download::all();
        return DownloadResource::collection($Downloads);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Download  $download
     * @return \Illuminate\Http\Response
     */
    public function show(Download $download)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Download  $download
     * @return \Illuminate\Http\Response
     */
    public function edit(Download $download)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Download  $download
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Download $download)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Download  $download
     * @return \Illuminate\Http\Response
     */
    public function destroy(Download $download)
    {
        //
    }

    public function snapshot(Request $request, Download $download)
    {
        try 
        {
            $sn = new \App\Snapshot();

            $sn->download_id = $download->id;
            $sn->filename = $request->file('image_file')->store('snapshots');
            $sn->timestamp = $request->timestamp;

            //create a thumb and save
            $filename = pathinfo($sn->filename, PATHINFO_FILENAME);
            $extension = pathinfo($sn->filename, PATHINFO_EXTENSION);

            $img = Image::make(request()->file('image_file')->getRealPath());
            $img->resize(null, 100, function ($constraint) {
                $constraint->aspectRatio();
            });

            $thumb = storage_path('app/snapshots') . '/' . $filename . "_thumb." . $extension;

            $img->save($thumb);

            $sn->thumb_filename = 'snapshots' . '/' . $filename . "_thumb." . $extension;

            $sn->save();

            return response()->json('success', 200);
            
        } catch (Exception $e) {
            (new SlackAgent())->notify(new GenericException(null, $request->user(), $e));

            return response()->json(['error' => $e], 500);
        }
    }

    public function snapshots(Request $request, Download $download)
    {
        return \App\Http\Resources\Snapshot::collection($download->Snapshots);
    }
}
