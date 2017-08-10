@extends('layouts.app')
@section('header')
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
    #map {
        height: 75%;
        width: 50%;
    }
    /* Optional: Makes the sample page fill the window. */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    .contextmenu{
        visibility:hidden;
        background:#ffffff;
        border:1px solid #8888FF;
        z-index: 10;
        position: relative;
        width: 140px;
    }
    .contextmenu div{
        padding-left: 5px
    }
    .controls {
        background-color: #fff;
        border-radius: 2px;
        border: 1px solid transparent;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        box-sizing: border-box;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        height: 29px;
        margin-left: 17px;
        margin-top: 10px;
        outline: none;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
    }

    .controls:focus {
        border-color: #4d90fe;
    }
    .title {
        font-weight: bold;
    }
    #infowindow-content {
        display: none;
    }
    #map #infowindow-content {
        display: inline;
    }
    textarea {
      width: 300px;
      height: 150px;
    }
    </style>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.datetimepicker.css') }}"/ >
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>
@endsection
@section('content')
    <h3><b>Create a new Trip</b></h3>
    <div class="col-md-8 col-md-offset-1">
        <form enctype="multipart/form-data" id="form_upload_trip_cover" method="post" action="">
            <img id="trip_cover" src="/image/cover/default_cover.png" height="300" width="1200">
            <input name="trip_cover" type="file" id="upload_trip_cover">
            <input id="plans" type="hidden" name="plans">
            <input id="new_trip" type="hidden" name="new_trip">
        </form>
    </div>
    <br>
    <div class="col-md-12">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" id="trip_name">
        </div>
        <div class="form-group col-md-4">
            <label>Time Start:</label>
            <input type="text" id="time_start">
        </div>
        <div class="form-group col-md-4">
            <label>Time End:</label>
            <input type="text" id="time_end">
        </div>
        <div class="form-group col-md-8">
            <label class="col-md-2">Description:</label>
            <textarea id="description"></textarea>
        </div> 
    </div>

    <div class="col-md-12">
    <div class="form-group">
        <span>Total Distance:</span>
        <span id="total"></span>
    </div>  
    <input id="pac-input" class="controls" type="text"
            placeholder="Enter a location">
    <div class="col-md-6" id="map" class="context-menu-one"></div>
    <div class="col-md-6" id="plan_form" style="overflow-x: hidden; overflow-y: scroll; max-height: 500px;">
    </div>
    </div>
    <br>
    
<!--     Script -->

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTEHZLU2VKA-CTNLUVzi5KvaB9dk4u1u4&libraries=places&callback=initMap"
        async defer></script>
<script src="{{asset('js/jquery.datetimepicker.full.min.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        jQuery('#time_start').datetimepicker();
        jQuery('#time_end').datetimepicker();
    });
</script>
<script type="text/javascript" src="{{asset('js/create_trip.js')}}"></script>
<script type="text/javascript">
    // show image to preview before upload
    $(document).ready(function() {
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function (e) {
                    $('#trip_cover').attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#upload_trip_cover").change(function(){
            readURL(this);
        });
    });  
</script>
@endsection

</html>