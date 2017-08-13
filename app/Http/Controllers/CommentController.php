<?php

namespace App\Http\Controllers;

use App\Image;
use App\Comment;
use Illuminate\Http\Request;
use App\Logic\Image\ImageRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function add(CommentRequest $request)
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

    public function addSub(CommentRequest $request)
    {	
    	$comment = new Comment;
    	$comment->parent_id = $request->parent_id;
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
