<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
    #map {
        height: 65%;
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
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>

    </script>
  </head>
  <body>
    <input id="pac-input" class="controls" type="text"
            placeholder="Enter a location">
    <div class="col-md-6" id="map" class="context-menu-one"></div>
    <div id="plan_form" style="overflow-x: hidden; overflow-y: scroll; max-height: 500px;">
    </div>
    <br>
    <form class="col-md-9">
        {!! csrf_field() !!}
        <div class="form-group">
            <label>Lat:</label>
            <input type="text" name="lat" id="lat">
        </div>
        <div class="form-group">
            <label>Lng:</label>
            <input type="text" name="lng" id="lng">
        </div>
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name_road" id="name_road">
        </div>
        <div class="form-group">
            <span>Total Distance:</span>
            <span id="total"></span>
        </div>   
    </form>
    
<!--     Script -->

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTEHZLU2VKA-CTNLUVzi5KvaB9dk4u1u4&libraries=places&callback=initMap"
        async defer></script>
<script src="{{asset('js/jquery.datetimepicker.full.min.js')}}"></script>

<!-- <script type="text/javascript">
    $(document).ready(function() {
        jQuery('.time_start').datetimepicker();
        jQuery('.time_end').datetimepicker();
    });
</script> -->
<script>
    // In the following example, markers appear when the user clicks on the map.
    // Each marker is labeled with a single alphabetical character.
    var marker;
    var markers = [];
    var waypts = [];
    var plans = [];
    var places = [];
    var count_plan = -1;
    var end_here = 0;
    //init Map
    function initMap() {
        //init
        var bach_khoa = {lat: 21.008, lng: 105.843};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: bach_khoa
        });
        //start search box
        var input = document.getElementById('pac-input');

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        autocomplete.addListener('place_changed', function() {
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            return;
          }

          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(15);
          }

        });
        //end search box
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer({
          draggable: false,
          map: map,
        });
        var geocoder = new google.maps.Geocoder;
        var infowindow = new google.maps.InfoWindow;

        //1
        var i = 0 ;
        google.maps.event.addListener(map, "rightclick",function(e){
            //show Context Menu For Map
            showContextMenu(e.latLng,0);
            // no marker on map
            if(markers.length == 0) {
                $('#add_marker > div').text('Start here');
            }
            //click Add Marker to add marker in Map
            $('#add_marker').click(function(){
                directionsDisplay.setMap(map);
                $('.contextmenu').remove();
                var lat = e.latLng.lat();
                var lng = e.latLng.lng();
                $('#lat').val(lat);
                $('#lng').val(lng);        
                placeMarkerAndAddMarker(e.latLng,map);
                geocodeLatLngMarker(geocoder, map, markers[markers.length - 1]);
                //when has more than 2 marker ---> show Display Route 
                showDisplayRoute();

                //dragend for New marker (Last Marker)
                markers[markers.length - 1].addListener('dragend',function(event) {
                      dragendMarker(event);
                });
                //
                function dragendMarker(e) {
                    var index_of_marker = markers.findIndex(function(marker) {
                            return (e.latLng.lat() === marker.getPosition().lat()) && (e.latLng.lng() === marker.getPosition().lng());
                    });
                    geocodeLatLngUpdateMarker(geocoder, map, e);
                    if(end_here == 0){
                        showDisplayRoute();
                    }else{
                        showDisplayRouteToEnd();
                    }
                    //geocode LatLng and Update Place in array
                    function geocodeLatLngUpdateMarker(geocoder, map ,e) {
                        var lat = e.latLng.lat();
                        var lng = e.latLng.lng();
                        var latlng = { lat: parseFloat(lat),lng: parseFloat(lng) };
                        //geocoder from marker
                        geocoder.geocode({'location': latlng}, function(results, status) {
                          if (status === 'OK') {
                            if (results[1]) {
                              map.setZoom(11);
                              place = results[1].formatted_address;
                              //update place at index_of_marker
                              places.splice(index_of_marker,1,place);
                              console.log(places);
                            } else {
                              window.alert('No results found');
                            }
                          } else {
                            window.alert('Geocoder failed due to: ' + status);
                          }
                        });
                    }                 
                }

                //Show Context for new marker( last marker)
                markers[markers.length - 1].addListener('rightclick',function rightclickMarker(e) {
                    //Show Context Menu for Marker
                    showContextMenu(e.latLng,1); 
                    //return index of marker in markers Array
                    var index_of_marker = markers.findIndex(function(marker) {
                            return (e.latLng.lat() === marker.getPosition().lat()) && (e.latLng.lng() === marker.getPosition().lng());
                    });
                    console.log(index_of_marker);
                    //if marker is first marker
                    if(index_of_marker == 0) {
                        $('#prev_marker').remove();
                    }
                    // if marker is last marker
                    if(index_of_marker == (markers.length - 1) && end_here == 0) {
                        $('#next_marker').remove();
                        $('<a id="end_here"><div class="context">End Here</div></a>').appendTo('.contextmenu');
                    }

                    //remove marker 
                    $('#remove_marker').click(function() {
                        // remove a plan in array and update DOM
                        if(index_of_marker == 0) {
                            $('#plan'+index_of_marker).remove();
                            for (var i = 1; i < plans.length; i++) {
                                $('#from'+i).attr("id",'from'+(i-1));
                                $('#to'+i).attr("id",'to'+(i-1));
                                $('#time_start'+i).attr("id",'time_start'+(i-1));
                                $('#time_end'+i).attr("id",'time_end'+(i-1));
                                $('#vehicle'+i).attr("id",'vehicle'+(i-1));
                                $('#activity'+i).attr("id",'activity'+(i-1));
                            }
                            plans.splice(index_of_marker,1);
                        }else if(index_of_marker == (markers.length - 1) && end_here == 0) {
                            $('#plan'+(index_of_marker-1) ).remove();
                            plans.splice(index_of_marker -1 ,1);
                        }else if(index_of_marker == (markers.length - 1) && end_here == 1) {
                            $('#plan'+index_of_marker).remove();
                            plans[index_of_marker - 1].to = plans[index_of_marker].to;
                            plans.splice(index_of_marker,1);
                        }
                        else{
                            //
                            
                            plans[index_of_marker - 1].to = plans[index_of_marker].to;
                            plans.splice(index_of_marker,1);
                        }

                        console.log('count_plan'+count_plan);
                        count_plan --;

                        $('.contextmenu').remove();
                        markers[index_of_marker].setMap(null);
                        var marker_remove = markers.splice(index_of_marker,1);
                        places.splice(index_of_marker,1);
                        console.log(places);
                        //if not click end here
                        if(end_here == 0) {
                            showDisplayRoute(); 
                        }else {
                            showDisplayRouteToEnd(); 
                        }
                    });

                    //add marker after a marker
                    $('#next_marker').click(function() {
                        //create a marker after choosed marker
                        var lat = (markers[index_of_marker].getPosition().lat() + markers[index_of_marker+1].getPosition().lat())/2;
                        var lng = (markers[index_of_marker].getPosition().lng() + markers[index_of_marker+1].getPosition().lng())/2;
                        //set marker on map
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(lat,lng),
                            map: map,
                            draggable:true
                        });
                        //insert after choosing marker
                        markers.splice(index_of_marker+1,0,marker);
                        showDisplayRoute(); 
                        //dragend for New marker (Last Marker)
                        marker.addListener('dragend',function(e) {
                            dragendMarker(e);
                        });
                        marker.addListener('rightclick',function(e) {
                            rightclickMarker(e);
                        });
                    });

                    //add marker after a marker
                    $('#prev_marker').click(function() {
                        //create a marker after choosed marker
                        var lat = (markers[index_of_marker].getPosition().lat() + markers[index_of_marker-1].getPosition().lat())/2;
                        var lng = (markers[index_of_marker].getPosition().lng() + markers[index_of_marker-1].getPosition().lng())/2;
                        //set marker on map
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(lat,lng),
                            map: map,
                            draggable:true
                        });
                        //insert after choosing marker
                        markers.splice(index_of_marker,0,marker);
                        showDisplayRoute(); 
                        //dragend for New marker (Last Marker)
                        marker.addListener('dragend',function(e) {
                            dragendMarker(e);
                        });
                        marker.addListener('rightclick',function(e) {
                            rightclickMarker(e);
                        });
                    });

                    //Click end here
                    $('#end_here').click(function() {
                        end_here = 1;
                        count_plan++;
                        console.log(count_plan);
                        var plan_form = createFormPlan();
                        $('#plan_form').append(plan_form);
                        $('<button name="finish_trip" id="finish_trip">Finish Trip</button>').appendTo('#plan_form');
                        showDisplayRouteToEnd();
                        var plan = {};
                        plan.from = places[markers.length -1];
                        plan.to = places[0];
                        $('#from'+count_plan).val(plan.from);
                        $('#to'+count_plan).val(plan.to);
                        plans.push(plan);
                        console.log(plans);
                        jQuery('.datetimepicker'+count_plan).datetimepicker();
                        //set event for Finish Trip Button
                        $('#finish_trip').click(function() {
                            // save to array  
                            for (var i = 0; i < plans.length; i++) {
                                plans[i].from = $('#from'+i).val();
                                plans[i].to = $('#to'+i).val();
                                plans[i].time_start = $('#time_start'+i).val();
                                plans[i].time_end = $('#time_end'+i).val();
                                plans[i].vehicle = $('#vehicle'+i).val();
                                plans[i].activity = $('#activity'+i).val();
                            }

                            //sending data with ajax
                        });  
                    });

                });     //End Show Context for Marker
                /*** 
                ***Update DOM
                ***/
                //create a plan
                var plan = {};
                var flag = 0;
                function createFormPlan() {
                        var plan_form = '<fieldset id="plan'+count_plan+'">' 
                                    +'<legend>Plan '+(count_plan + 1)+'</legend>'
                                    +'<div>'
                                        +'<div class="col-md-8">'
                                            +'<div class="form-group col-md-3">'
                                            +'<label for="from">From: </label>'
                                            +'<input type="text" id="from'+count_plan+'">'
                                            +'</div>'
                                            +'<div class="form-group col-md-3 col-md-offset-4">'
                                            +'<label for="to">To: </label>'
                                            +'<input type="text" id="to'+count_plan+'">'
                                            +'</div>'
                                            +'<div class="form-group col-md-3">'
                                            +'<label for="time_start">Time start: </label>'
                                            +'<input type="text" class="datetimepicker'+count_plan+'" id="time_start'+count_plan+'" >'
                                            +'</div>'
                                            +'<div class="form-group col-md-3 col-md-offset-4">'
                                            +'<label for="time_end">Time end: </label>'
                                            +'<input type="text" class="datetimepicker'+count_plan+'" id="time_end'+count_plan+'">'
                                            +'</div>'
                                            +'<div class="form-group col-md-3">'
                                            +'<label for="vehicle">Vehicle: </label>'
                                            +'<input type="text" id="vehicle'+count_plan+'">'
                                            +'</div>'
                                            +'<div class="form-group col-md-3 col-md-offset-4">'
                                            +'<label for="to">Activity: </label>'
                                            +'<textarea id="activity'+count_plan+'"></textarea>'
                                            +'</div>'
                                        +'</div>'
                                    +'</div>'
                                    +'</fieldset>';
                        return plan_form;                      
                };
                
                function showPlan() {
                    var plan = {};
                    plan.from = places[markers.length -2];
                    plan.to = places[markers.length - 1];
                    $('#from'+count_plan).val(plan.from);
                    $('#to'+count_plan).val(plan.to);
                    plans.push(plan);
                    console.log(plans);
                    jQuery('.datetimepicker'+count_plan).datetimepicker();

                }

                //if has more than 1 marker
                if( markers.length > 1) {
                    count_plan++;
                    $(document).ready(function() {
                        var plan_form = createFormPlan();
                        $('#plan_form').append(plan_form);

                    });
                    setTimeout(showPlan,1000);      
                }

                //geocode LatLng and Add Place to array
                function geocodeLatLngMarker(geocoder, map ,marker) {
                    var lat = marker.getPosition().lat();
                    var lng = marker.getPosition().lng();
                    var latlng = { lat: parseFloat(lat),lng: parseFloat(lng) };
                    //geocoder from marker
                    geocoder.geocode({'location': latlng}, function(results, status) {
                      if (status === 'OK') {
                        if (results[1]) {
                          map.setZoom(11);
                          place = results[1].formatted_address;
                          places.push(place);
                        } else {
                          window.alert('No results found');
                        }
                      } else {
                        window.alert('Geocoder failed due to: ' + status);
                      }
                    });
                }

            }); // End click Add Marker function
            
        });
        //2
        function showContextMenu(caurrentLatLng, number) {
            var projection;
            var contextmenuDir;
            projection = map.getProjection();
            $('.contextmenu').remove();
            contextmenuDir = document.createElement("div");
            contextmenuDir.className  = 'contextmenu';
            switch(number){
                case 0: //Show Context Menu For Map
                    contextmenuDir.innerHTML = '<a id="add_marker"><div class="context">Add Marker</div></a>'
                                   + '<a id="change"><div class="context">Change</div></a>'
                                   + '<a id="menu3"><div class="context">menu item 3</div></a>';
                    break;

                case 1: //Show Context Menu For Marker
                    contextmenuDir.innerHTML = '<a id="remove_marker"><div class="context">Remove Marker</div></a>'
                                       + '<a id="next_marker"><div class="context">Add Marker From Here</div></a>'
                                       + '<a id="prev_marker"><div class="context">Add Marker To Here</div></a>';
                    break;
            }

            $(map.getDiv()).append(contextmenuDir);

            setMenuXY(caurrentLatLng);

            contextmenuDir.style.visibility = "visible";
        }
        //3
        function getCanvasXY(caurrentLatLng){
            var scale = Math.pow(2, map.getZoom());
            var nw = new google.maps.LatLng(
                map.getBounds().getNorthEast().lat(),
                map.getBounds().getSouthWest().lng()
            );
            var worldCoordinateNW = map.getProjection().fromLatLngToPoint(nw);
            var worldCoordinate = map.getProjection().fromLatLngToPoint(caurrentLatLng);
            var caurrentLatLngOffset = new google.maps.Point(
                Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale),
                Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale)
            );
            return caurrentLatLngOffset;
        }
        //4
        function setMenuXY(caurrentLatLng){
            var mapWidth = $('#map_canvas').width();
            var mapHeight = $('#map_canvas').height();
            var menuWidth = $('.contextmenu').width();
            var menuHeight = $('.contextmenu').height();
            var clickedPosition = getCanvasXY(caurrentLatLng);
            var x = clickedPosition.x;
            var y = clickedPosition.y;

            if((mapWidth - x ) < menuWidth)//if to close to the map border, decrease x position
                x = x - menuWidth;
            if((mapHeight - y ) < menuHeight)//if to close to the map border, decrease y position
                y = y - menuHeight;

            $('.contextmenu').css('left',x  );
            $('.contextmenu').css('top',y );
        };

        //Remove Context Menu when click to map
        google.maps.event.addListener(map, 'click', function() {
            $('.contextmenu').remove();
        });

        //when has more than 2 marker ---> show Display Route 
        function showDisplayRoute() {
            if(markers.length > 1){
                    resetAndUpdateWaypts();
                    directionsDisplay.addListener('directions_changed', function() {
                      computeTotalDistance(directionsDisplay.getDirections());
                    });
                    console.log(markers);
                    directionsDisplay.setMap(map);
                    displayRoute(markers[0].getPosition(), waypts[waypts.length - 1].location, directionsService, directionsDisplay);
                }else{
                    waypts = [];
                    directionsDisplay.setMap(null);
                    $('#total').html(0 + "km");
                }   
        }

        //when has more than 2 marker ---> show Display Route 
        function showDisplayRouteToEnd() {
            if(markers.length > 1){
                    resetAndUpdateWaypts();
                    directionsDisplay.addListener('directions_changed', function() {
                      computeTotalDistance(directionsDisplay.getDirections());
                    });
                    directionsDisplay.setMap(map);
                    displayRoute(markers[0].getPosition(), markers[0].getPosition(), directionsService, directionsDisplay);
                }else{
                    waypts = [];
                    directionsDisplay.setMap(null);
                    $('#total').html(0 + "km");
                }   
        }
        
    } // End function initMap()

    // place Marker and Pan to it
    function placeMarkerAndAddMarker(latLng, map) {
        marker = new google.maps.Marker({
            position: latLng,
            map: map,
            draggable:true
        });
        markers.push(marker);
        map.panTo(latLng);
        if(markers.length > 1){
            waypts.push({
              location: marker.getPosition(),
            });
        }
    }

    //geocode LatLng 
    function geocodeLatLng(geocoder, map, infowindow) {
        var lat1 = $('#lat').val();
        var lng1 = $('#lng').val();
        var latlng = { lat: parseFloat(lat1),lng: parseFloat(lng1) };
        geocoder.geocode({'location': latlng}, function(results, status) {
          if (status === 'OK') {
            if (results[1]) {
              map.setZoom(11);
              infowindow.setContent(results[1].formatted_address);
              infowindow.open(map, marker);
              $('#name_road').val(results[1].formatted_address);    //set name for input text
            } else {
              window.alert('No results found');
              $('#name_road').val('No results found');
            }
          } else {
            window.alert('Geocoder failed due to: ' + status);
            $('#name_road').val('Geocoder failed ');
          }
        });
      }

    //Display Route
    function displayRoute(origin, destination, service, display) {
        console.log("Display Route");
        service.route({
          origin: origin,
          destination: destination,
          waypoints: waypts,
          travelMode: 'DRIVING',
         // avoidTolls: true
        }, function(response, status) {
          if (status === 'OK') {
            display.setDirections(response);
          } else {
            alert('Could not display directions due to: ' + status);
          }
        });
      }

    //
    function computeTotalDistance(result) {
        var total = 0;
        var myroute = result.routes[0];
        for (var i = 0; i < myroute.legs.length; i++) {
          total += myroute.legs[i].distance.value;
        }
        total = total / 1000;
        $(document).ready(function() {
            $('#total').html(total + " km");
        });
    }

    // Sets the map on all markers in the array.
      function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
      }

      // Removes the markers from the map, but keeps them in the array.
      function clearMarkers() {
        setMapOnAll(null);
      }

      //reset and update waypoint
      function resetAndUpdateWaypts(){
        waypts = []; // reset wapts array
        for(var k = 1; k < markers.length; k++) {   // add way point
            waypts.push({
              location: markers[k].getPosition(),
            });
        }
      }

</script>
</body>

</html>