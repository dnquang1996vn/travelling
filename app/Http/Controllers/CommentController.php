<?php

namespace App\Http\Controllers;

use App\Image;
use App\Comment;
use Illuminate\Http\Request;
use App\Logic\Image\ImageRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CommentController extends Controller
{
    public function add(Request $request)
    {	
    	$comment = new Comment;
    	$comment->trip_id = $request->trip_id;
    	$comment->user_id = $request->user_id;
    	$comment->text    = $request->text;
    	$comment->save();
    	$user = $comment->user;
    	$result = ['comment' => $comment,
    			   'user'    => $user,
				];
    	return $result;
    }
}
