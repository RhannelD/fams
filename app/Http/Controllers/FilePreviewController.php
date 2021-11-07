<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarResponseFile;
use Illuminate\Support\Facades\Storage;

class FilePreviewController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ( !Auth::check() )
            return abort(401);

        if ( !ScholarResponseFile::where('id', $id)->exists() )
            return abort(404);

        $file = ScholarResponseFile::where('id', $id)
            ->when(!Auth::user()->is_admin() && !Auth::user()->is_officer(), function ($query) {
                $query->whereHas('response', function ($query) {
                    $query->where('user_id', Auth::id());
                });
            })
            ->first();
        
        if ( is_null($file) ) 
             return abort(401);

        $is_desktop = new \Jenssegers\Agent\Agent();
        $is_desktop = $is_desktop->isDesktop();
        if ($is_desktop) {
            return view('file.file-preview', [
                    'response_file' => $file,
                ]);
        }
        return Storage::download('files/'.$file->file_url);
    }
}
