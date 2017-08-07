@extends('layouts.app')

@section('content')
<div class="container" style = "background: #f2f2f2">
    <section>
        <p class="text-center">
            <h1 class="text-center"> {{$trip->name}} </h1>
            <h3 class="text-center">From {{$trip->starting_time}} to {{$trip->ending_time}}</h3>
            <h4 class="text-center" style="font-style: italic;">
                {{$trip->description}}
            </h4> 
            @if ($isOwner)
            fuck
            @else
                <span class="text-center" style="display: inline;">
                    @if ($notFollowed)
                        <button class="btn btn-primary text-center"
                        id = "followBtn" value="0">
                            Follow
                        </button>
                    @else
                        <button class="btn btn-primary text-center"
                        id = "followBtn" value="1">
                            Unfollow
                        </button>
                    @endif

                    @if ($joined)
                        <button class="btn btn-primary text-center" 
                        id = "joinBtn" value="0">
                            Out trip
                        </button>
                    @else
                        @if ($requested)
                            <button class="btn btn-primary text-center" 
                            id = "joinBtn" value="0">
                                Cancel request
                            </button>
                        @else
                            <button class="btn btn-primary text-center" 
                            id = "joinBtn" value="0">
                                Join trip
                            </button>
                        @endif
                    @endif
                </span>
            @endif
            @can('update', $trip)
            sdfsd
            @endcan
            @cannot('join',$trip)
            aaaaaaa
            @endcan
            <input type="hidden" id = "trip_id" value="{{$trip->id}}">
            <input type="hidden" id = "user_id" value="{{Auth::id()}}">
        </p>
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
        <li role="presentation">
            <a href="#requestDiv" aria-controls="messages" role="tab" data-toggle="tab">Request</a>
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
                    <!-- lam mot cai table -->
                    <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Email</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>John</td>
                            <td>Doe</td>
                            <td>john@example.com</td>
                          </tr>
                          <tr>
                            <td>Mary</td>
                            <td>Moe</td>
                            <td>mary@example.com</td>
                          </tr>
                          <tr>
                            <td>July</td>
                            <td>Dooley</td>
                            <td>july@example.com</td>
                          </tr>
                        </tbody>
                      </table>


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
            fuck huy
        </div>
        <div role="tabpanel" class="tab-pane" id="requestDiv">
            sdfi
        </div>
    </div> <!-- end tab content -->
</div>
@endsection
    
@section('jq_lib') <!-- thu vien cua jequerry -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src = "{{asset('js/loadmore.js')}}"></script>
@endsection 

@section('sc')
<script type="text/javascript" src = "{{asset('js/handleApp/trip.js')}}"></script>
@endsection
<script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -34.397, lng: 150.644},
          zoom: 8
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTEHZLU2VKA-CTNLUVzi5KvaB9dk4u1u4&callback=initMap"
async defer></script>