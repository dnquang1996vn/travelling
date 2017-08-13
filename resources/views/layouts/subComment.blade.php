<div class="subComment">
    <div class = "row">
        <div class="col-lg-1">
            <img src="{{asset($comment->user->avatar)}}" class="comment_avatar">
        </div>
    
        <div class="col-lg-9">
            <div class="subCommentContent">
                <a href="/profile/{{$comment->user->id}}">
                <strong style="color: blue">
                    {{$child->user->name}}&nbsp
                </strong>
                </a>
                {{$child->text}}
                <div class="imageContent">
                    @foreach($child->images as $img)
                    <img src="{{asset($img->url)}}" class="comment_avatar">
                    @endforeach
                </div>
                <br>
                <div class = "respond" style="display: inline;">
                    &nbsp&nbsp&nbsp
                    <a href=""javascript:;""> Like </a>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
        </div>
        <br><br>
    </div>
</div>