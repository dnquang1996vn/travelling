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
            <p class="text-center">
            <button class="btn btn-primary text-center">Follow</button>
            <button class="btn btn-primary text-center">Join</button>
            </p>
        </p>
        <div class="thumbnail cover">
            <img class = "cover_trip" src = "{{asset($trip->cover)}}">
        </div>
    </section>
    <section>
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
                    <div class="row" style="overflow-y: auto; overflow-x: hidden; max-height: 700px ">
                    
                    <ul class = "list" id = "list">
                    @foreach ($joined_trips as $joined_trip)
                    <li>
                        <div class="col-lg-4">   
                            <div class="thumbnail avatar_trip">
                                <img src="{{asset($joined_trip->user->avatar)}}" class="profile_avatar">
                                <div class="caption">
                                    <a href="{{route('profile',$joined_trip->user->id)}}">        
                                        <h4 class="text-center" style="font-style: 4px"> 
                                        <strong> {{$joined_trip->user->name}}</strong>
                                        </h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                    </ul>
                    <button class="btn btn-primary" id = "loadMore"> load more</button>
                    </div>
                </div> <!-- end joined user -->
            </div><!--  end col-lg-4 -->
        </div>
    </section>
</div>
@endsection
    
@section('jq_lib') <!-- thu vien cua jequerry -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src = "{{asset('js/loadmore.js')}}"></script>
@endsection 

@section('sc')
<script type="text/javascript">
$(document).ready(function(){
   $("#list").loadMore({
            selector: 'li',
            loadBtn: '#loadMore',
            limit: 6,
            load: 6,
            animate: true,
            animateIn: 'fadeInUp'
        });
});

$(window).scroll(function () {
    if ($(this).scrollTop() > 50) {
        $('.totop a').fadeIn();
    } else {
        $('.totop a').fadeOut();
    }
});
console.log('sdoijjo  ')
</script>

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