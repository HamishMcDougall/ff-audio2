<?php

namespace App\Http\Controllers;


use FFMpeg\FFMpeg;
use App\Recording;
use FFMpeg\Format\Audio\Aac;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RecordingController extends Controller
{
        public function postRecording(Request $request)
       {
          // Get the POSTed file
             $file = $request->file('recording');
             // Create a random file name
             $fileName = hash('md5', uniqid(mt_rand(), true), false) . '.ogg';
             // Move the file to the storage directory
             $file->move(storage_path(), $fileName);


// work out how to convert the audio fomat


             // Save our recording
            DB::table('recordings')->insert([
                'filename'      => $fileName,
                'path'          => storage_path(),
                'mime_type'     => $file->getClientMimeType(),
                'content_size'  => $file->getClientSize(),
            ]);

       }
       public function getRecording(Request $request, $id)
       {
         // Fetch our recording by ID, or throw an exception
         $recording = Recording::findOrFail($id);
         // Return our recording data
         return response()->json($recording, 200);
       }
}
