<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Logic\Image\ImageRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UploadController extends Controller
{
    public function dropzone()
    {
        return view('backupUpload');
    }

    /**
     * Image Upload Code
     *
     * @return void
     */
    public function dropzoneStore(Request $request)
    {	
        $image = $request->file('file');
        $imageName = $image->getClientOriginalName();
       $a= $image->move(public_path('avatar_comment'),$imageName);
        return $a;

    }

    public function delete(Request $request){
    	$filename = $request->id;
    	//dd($filename);
        if(!$filename)
        {
            return 0;
        }
        Storage::delete('avatar_comment/'.$filename); 
       // $response = $this->image->delete( $filename );

        return "dsf";
    }
}
