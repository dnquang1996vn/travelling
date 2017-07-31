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
                <h4><a href="{{route('profile',$new->id)}}"><center>{{$new->name}}</center></a>
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
                        <h5 class="text-center">20 persons joined</h5>
                        <h5 class="text-center">
                   			10 persons followed
                        </h5>
                	</div>
                </div>	
                <p></p>
            </div>
            <div class="ratings">
                <p class="pull-right">15 reviews</p>
                <p>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                </p>
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
                <h4><a href="#"><center>{{$hot->name}}</center></a>
                <h5 class="text-center">{{$hot->starting_time}} to {{$hot->ending_time}}</h5>
                </h4>
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <h5 class="text-center"> owner: </h5>
                        <h5 class="text-center">
                            <a href="#">{{$hot->owner->name}}</a>
                        </h5>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <h5 class="text-center">20 persons joined</h5>
                        <h5 class="text-center">
                            10 persons followed
                        </h5>
                    </div>
                </div>  
                <p></p>
            </div>
            <div class="ratings">
                <p class="pull-right">15 reviews</p>
                <p>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                </p>
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
                <h4><a href="#"><center>{{$all->name}}</center></a>
                <h5 class="text-center">{{$all->starting_time}} to {{$all->ending_time}}</h5>
                </h4>
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <h5 class="text-center"> owner: </h5>
                        <h5 class="text-center">
                            <a href="#">{{$all->owner->name}}</a>
                        </h5>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <h5 class="text-center">20 persons joined</h5>
                        <h5 class="text-center">
                            10 persons followed
                        </h5>
                    </div>
                </div>  
                <p></p>
            </div>
            <div class="ratings">
                <p class="pull-right">15 reviews</p>
                <p>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                </p>
            </div>
        </div> <!-- end col-lo=g-4 -->
    @endforeach
    </section>
</div>
@endsection
@section('script')
<script src="{{ asset('js/handleApp/listTrip.js') }}"></script>
@endsection
