<?php

namespace App\Http\Controllers;

use App\Image;
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
        $imageName = time().$image->getClientOriginalName();
        $image->move(public_path('avatar_comment'),$imageName);
        $newImage = new Image;
        $newImage->comment_id = $request->comment_id;
        $newImage->url = 'avatar_comment/'.$imageName;
        $newImage->save();
        return $newImage;
    }

    public function delete(Request $request){
    	$filename = $request->file;
        if(!$filename)
        {
            return 0;
        }
        //$img = Image::find($filename['id'])->delete();
        Storage::delete('avatar_comment/'.$filename); 
    }

    public function view(Request $request){
        return view('load');
    }

    public function getServerImages(Request $request,$id)
    {   
       
        $images = Image::where('comment_id',$id)->get();
        return $images;
    }
}
