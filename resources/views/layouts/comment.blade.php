<div class="comment">
    <div class = "row">
        <div class="col-lg-1">
            <img src="{{asset($comment->user->avatar)}}" class="comment_avatar">
        </div>
    
        <div class="col-lg-9 commentPart">
            <div class="commentContent">
                <a href="/profile/{{$comment->user->id}}">
                <strong style="color: blue">
                    {{$comment->user->name}}&nbsp
                </strong>
                </a>
                {{$comment->text}}
                <div class="imageContent">
                    @foreach($comment->images as $img)
                    <img src="{{asset($img->url)}}" class="comment_avatar">
                    @endforeach
                </div>
                <br>
                <div class = "respond" style="display: inline;">
                    &nbsp&nbsp&nbsp
                    <a href=""javascript:;""> Like </a>
                    &nbsp&nbsp&nbsp
                    <a href=""javascript:;"" class="replyCommentBtn"> Reply</a>
                </div>
                <div class="subCommentList">
                    @foreach($comment->children as $child)
                        @include('layouts.subComment') 
                    @endforeach
                </div>
                
            <div class="add_comment">
                <div class = "row" id = "addCommentDiv">
                    <div class="col-lg-1">
                        <img src="{{asset($user->avatar)}}" class="comment_avatar">
                    </div>
                    <div class="col-lg-9">
                        <textarea rows="4" cols="60" placeholder="Comment here" class="commentContent"></textarea>
                    </div>
                    <div class="col-lg-1">
                        <button class="btn btn-primary subCommentBtn" value="{{$comment->id}}"> submit</button>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-lg-2">
        </div>
        <br><br>
    </div>
</div>