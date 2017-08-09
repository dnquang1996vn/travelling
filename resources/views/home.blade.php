@extends('layouts.app')

@section('content')
<div class="container" style = "background: #f2f2f2">
        <h1>
            <strong>Home</strong>
        </h1>

        <h3> 
        	<strong>Newest trips</strong>
        </h3>
    <section class="regular slider">
    @foreach($newestTrips as $new)
        <div class="thumbnail">
            <img src="{{asset($new->cover)}}" alt="">
            <div class="caption">
                <h4><a href="{{route('trip',$new->id)}}"><center>{{$new->name}}</center></a>
                <h5 class="text-center">{{$new->starting_time}} to {{$new->ending_time}}</h5>
                </h4>
                <div class="row">
                	<div class="col-md-6 col-lg-6">
                        <h5 class="text-center"> owner: </h5>
                        <h5 class="text-center">
                        	<a href="{{route('profile',$new->owner->id)}}">{{$new->owner->name}}</a>
                        </h5>
                	</div>
                	<div class="col-md-6 col-lg-6">
                        <h5 class="text-center">
                        {{$new->joined_trips->count()}} persons joined</h5>
                        <h5 class="text-center">
                   		{{$new->followed_trips->count()}} persons followed
                        </h5>
                	</div>
                </div>	
                <p></p>
            </div>
        </div> <!-- end col-lo=g-4 -->
    @endforeach
    </section>


    <h3> <strong> Hottest trips <strong> </h3>
    <section class="regular slider">
    @foreach($hottestTrips as $hot)
        <div class="thumbnail">
            <img src="{{asset($hot->cover)}}" alt="">
            <div class="caption">
                <h4><a href="{{route('trip',$hot->id)}}"><center>{{$hot->name}}</center></a>
                <h5 class="text-center">{{$hot->starting_time}} to {{$hot->ending_time}}</h5>
                </h4>
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <h5 class="text-center"> owner: </h5>
                        <h5 class="text-center">
                            <a href="{{route('profile',$hot->owner->id)}}">{{$hot->owner->name}}</a>
                        </h5>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <h5 class="text-center">{{$hot->joined_trips->count()}} persons joined</h5>
                        <h5 class="text-center">
                        {{$hot->followed_trips->count()}} persons followed
                        </h5>
                    </div>
                </div>  
                <p></p>
            </div>
        </div> <!-- end col-lo=g-4 -->
    @endforeach
    </section>

    <h3> <strong> All trips <strong> </h3>
    <section class="regular slider">
    @foreach($allTrips as $all)
        <div class="thumbnail">
            <img src="{{asset($all->cover)}}" alt="">
            <div class="caption">
                <h4><a href="{{route('trip',$all->id)}}"><center>{{$all->name}}</center></a>
                <h5 class="text-center">{{$all->starting_time}} to {{$all->ending_time}}</h5>
                </h4>
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <h5 class="text-center"> owner: </h5>
                        <h5 class="text-center">
                            <a href="{{route('profile',$all->owner->id)}}">{{$all->owner->name}}</a>
                        </h5>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <h5 class="text-center">{{$all->joined_trips->count()}} persons joined</h5>
                        <h5 class="text-center">
                            {{$all->followed_trips->count()}} persons followed
                        </h5>
                    </div>
                </div>  
                <p></p>
            </div>
        </div> <!-- end col-lo=g-4 -->
    @endforeach
    </section>
</div>
@endsection

@section('jq_lib') <!-- thu vien cua jequerry -->
<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
@endsection

@section('sc') <!-- code script cua trang -->
<script src="{{ asset('js/handleApp/listTrip.js') }}"></script>
@endsection
