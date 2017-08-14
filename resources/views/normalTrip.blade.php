@extends('layouts.app')

@section('content')
<div class="container" style = "background: #f2f2f2">
    <section>
        <p class="text-center">
            <h1 class="text-center"> {{$trip->name}} </h1>
            <h3 class="text-center">From {{$trip->starting_time}} to {{$trip->ending_time}}</h3>
            <div align="center">
                {!! $trip->description !!}
            </div>
            <div class="text-center" style="display: inline;">
                @can ('follow', $trip)
                    <button class="btn btn-primary text-center"
                    id = "followBtn" value="0">
                        Follow
                    </button>
                @endcan
                @can ('unfollow',$trip)
                    <button class="btn btn-primary text-center"
                    id = "followBtn" value="1">
                        Unfollow
                    </button>
                @endcan

                @can ('outTrip', $trip)
                    <button class="btn btn-primary text-center" 
                    id="joinBtn" value="2">
                        Out trip
                    </button>
                @endcan

                @can ('joinRequest', $trip)
                    <button class="btn btn-primary text-center" 
                    id="joinBtn" value="0">
                        Join trip
                    </button>
                @endcan

                @can ('cancelRequest', $trip)
                    <button class="btn btn-primary text-center" 
                    id="joinBtn" value="1">
                        Cancel request
                    </button>
                @endcan
                <button class="btn btn-primary">
                    Status: 
                    @if ($trip->status == 0)
                        planning
                    @elseif ($trip->status == 1)
                        running
                    @elseif ($trip->status == 1)
                        done
                    @else
                        canceled
                    @endif
                </button>
            </div>
            <input type="hidden" id = "trip_id" value="{{$trip->id}}">
            <input type="hidden" id = "user_id" value="{{Auth::id()}}">
        </p>
        <!-- modal request join -->
        <div id="requestModal" class="modal fade" role="dialog"> 
            <div class="modal-dialog">
            <!-- modal content -->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send a joining request</h4>
                    </div>
                    <div class="modal-body">
                        <h3> <strong>Send a joining message to the trip's onwer</strong></h3>
                        <span style="color: red" id = "joinRequestError"></span>
                        <textarea cols = "60" rows ="5" id="messages"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id = "sendRequest">
                            Save 
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="thumbnail cover">
            <img class = "cover_trip" src = "{{asset($trip->cover)}}">
        </div>
    </section>
      <!-- Nav tabs  content-->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#detailDiv" aria-controls="home" role="tab" data-toggle="tab">Detail</a>
        </li>
        <li role="presentation">
            <a href="#discussDiv" aria-controls="profile" role="tab" data-toggle="tab">Discuss</a>
        </li>
    </ul>
   <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="detailDiv">
            <h2> <strong> Details</strong></h2>
            <div class="row">
                <div class="col-lg-8" style="border: solid;">
                    <h2 class="text-center"> Trip Schedule </h2>
                    <h4> Map </h4>
                    <div id="map"></div>
                    <hr>
                    <h4> Schedule </h4>
                <div id="accordion" >
                        @foreach( $plans as $i => $plan )
                        <h3>Plan {{$i+1}} : {{ $plan->src_name }} --> {{ $plan->dest_name }}</h3>
                        <div>
                            <p>From: {{ $plan->src_name }}</p>
                            <p>To: {{ $plan->dest_name }}</p>
                            <p>Time start: {{ $plan->starting_time }}</p>
                            <p>Time end: {{ $plan->ending_time }}</p>
                            <p>Vehicle: {{ $plan->vehicle }}</p>
                            <p>Activity:<br> {!! $plan->activity !!}</p>
                        </div>     
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4" style="border: solid;">
                    <h3 class="text-center"> Members </h3>
                    <h4><strong> Owner: </strong></h4>
                    <div class="thumbnail avatar_trip">
                        <img src="{{asset($trip->owner->avatar)}}" class="profile_avatar">
                        <div class="caption">
                            <a href="{{route('profile',$trip->owner->id)}}">        
                                <h4 class="text-center"> <strong> {{$trip->owner->name}} </strong> </h4>
                            </a>
                        </div>
                    </div>
                    <hr class="style2">
                    <div>
                        <h4><strong> joined Users:</strong></h4>
                        <div class="row scroll_list" id = "scroll_list">
                        @foreach ($joined_trips as $joined_trip)
                            <div class="col-lg-6 list_user">
                                <div class="thumbnail avatar_trip">
                                    <img src="{{asset($joined_trip->user->avatar)}}" class="profile_avatar">
                                    <div class="caption">
                                        <a href="{{route('profile',$joined_trip->user->id)}}">        
                                            <h4 class="text-center"> 
                                                <strong> {{$joined_trip->user->name}} </strong> 
                                            </h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <button class="btn btn-primary" id = "loadMore"> load more</button>
                        </div>
                    </div> <!-- end joined user -->
                </div><!--  end col-lg-4 -->
            </div>
        </div> <!-- end detail div -->
        <div role="tabpanel" class="tab-pane" id="discussDiv">
            <div class="add_comment">
                <div class = "row" id = "addCommentDiv">
                    <div class="col-lg-1">
                        <img src="{{asset($user->avatar)}}" class="comment_avatar">
                    </div>
                    <div class="col-lg-8">
                        <textarea rows="4" cols="80" placeholder="Comment here" class="commentContent"></textarea>
                        <form action="/load" method = "post" file = "true" enctype="maltipart/form-data" class="dropzone image" id ="image_upload" style="display: none">
                            {{ csrf_field() }}
                        </form>
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-info addDropzone" id = "addDropzone"> add image</button>
                        <button class="btn btn-primary commentSubmit" value=""> submit</button>
                    </div>
                </div>
            </div>
            <div id="commentList">
            @foreach($comments as $comment)
                @include('layouts.comment')
            @endforeach
            </div>
        </div>
    </div> <!-- end tab content -->
</div>
@endsection
    
@section('jq_lib') <!-- thu vien cua jequerry -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src = "{{asset('js/loadmore.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
    <script>
        $(document).ready(function() {
            $( "#accordion" ).accordion({
              collapsible: true
            });    
          });
    </script>
@endsection 

@section('sc')
<script type="text/javascript" src = "{{asset('js/handleApp/trip.js')}}"></script>
<script type="text/javascript" src = "{{asset('js/handleApp/dropzone.js')}}"></script>
@endsection
<script type="text/javascript">
    var sum_plans = "{{$plans->count()}}";
    var locations = [
        @foreach ($plans as $plan)
            [ "{{ $plan->src_lat }}", "{{ $plan->src_lng }}" ], 
        @endforeach
    ];
    var trip_id = "{{ $trip->id }}";
</script>
<script type="text/javascript" src = "{{asset('js/show_trip.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTEHZLU2VKA-CTNLUVzi5KvaB9dk4u1u4&callback=initMap"
        async defer></script>