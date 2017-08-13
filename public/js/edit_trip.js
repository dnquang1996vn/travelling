var marker;
var markers = [];
var waypts = [];
var plans = [];
var places = [];
var end_here = 0;
var new_trip = {};
//Update markers, places, plans From DB

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
    //update all marker,places and plans
    updateFromDB();

    //add Click to Finish Trip Button
    $('#finish_trip').click(function() {
        new_trip.name        = $('#trip_name').val();
        new_trip.time_start  = $('#time_start').val();
        new_trip.time_end    = $('#time_end').val();
        new_trip.description = $('#description').val();
        // save to array  
        updatePlans();
        function updatePlans() {
            for (var i = 0; i < plans.length; i++) {
                plans[i].from       = $('#from'+i).val();
                plans[i].to         = $('#to'+i).val();
                plans[i].time_start = $('#time_start'+i).val();
                plans[i].time_end   = $('#time_end'+i).val();
                plans[i].vehicle    = $('#vehicle'+i).val();
                plans[i].activity   = $('#activity'+i).val();
            }
            // add marker lat lng to Plan
            for( var j = 0; j < markers.length ;j++) {
                var last = markers.length - 1;
                if(j == 0) {
                    plans[0].src_lat = parseFloat(markers[0].getPosition().lat());
                    plans[0].src_lng = parseFloat(markers[0].getPosition().lng());
                    plans[last].dest_lat = parseFloat(markers[0].getPosition().lat());
                    plans[last].dest_lng = parseFloat(markers[0].getPosition().lng());
                }else{
                    plans[j-1].dest_lat =  parseFloat(markers[j].getPosition().lat());
                    plans[j-1].dest_lng =  parseFloat(markers[j].getPosition().lng());
                    plans[j].src_lat = parseFloat(markers[j].getPosition().lat());
                    plans[j].src_lng = parseFloat(markers[j].getPosition().lng());
                }
            }                                 
        }
        console.log(plans);
        //sending data with ajax
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'accepts': 'application/json',
            }
        });
        $('#plans').val(JSON.stringify(plans));
        $('#new_trip').val(JSON.stringify(new_trip));
        var data = new FormData($('#form_upload_trip_cover')[0]);
        //data.append("markers", JSON.stringify(markers));
        // data.append("new_trip",JSON.stringify(new_trip));
        $.ajax({
            url: '/edit_trip/'+trip_id,
            type: "post",
            datatType: "text",
            processData: false,
            contentType: false,
            data: data,
        
            success: function(data){
                $('#form_errors').remove();
                alert('success');
                window.location.replace("/");
            },
            error: function(data) {
                alert('failed');
                var errors = JSON.parse(data.responseText);
                var html_errors = '';
                console.log(errors);
                for(var prop in errors){
                    html_errors += "<p>"+errors[prop][0]+"</p>";
                }
                $('#form_errors').remove();
                $('<div class="alert alert-danger" id="form_errors">'+html_errors+'</div>').insertBefore('#trip_cover');
                console.log(html_errors); 
            }
        });
    }); 
    //
    function updateFromDB(){
        updateMarkersFromDB();   //update Markers Array
        updatePlacesFromDB();   //update Places Array
        updatePlansFromDB();    //update Plans Array
        setEventForMarkers(); //set Dragend and Menu Context for new all markers
        showDisplayRouteToEnd();
    }

    function updateMarkersFromDB() {
        for(var i = 0; i < sum_plans; i++){
            var latLng = {lat: parseFloat(locations[i][0]),lng: parseFloat(locations[i][1])};
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
    }

    function updatePlacesFromDB() {
        for(var i = 0; i< sum_plans; i++){
            var place = $('#from'+i).val();
            places.push(place);
        }
    }

    function updatePlansFromDB(i) {
        for(var i = 0; i < sum_plans; i++) {
            var plan        = {};
            plan.from       = $('#from'+i).val();
            plan.to         = $('#to'+i).val();
            plan.time_start = $('#time_start'+i).val();
            plan.time_end   = $('#time_end'+i).val();
            plan.vehicle    = $('#vehicle'+i).val();
            plan.activity   = $('#activity'+i).val();
            plans.push(plan);
        }    
    }

    function setEventForMarkers() {
        for(var i = 0; i < markers.length; i++) {
            console.log(markers[i]);
            var index_of_marker = i;
            !function outer(index_of_marker){
                //set Drag End Event
                markers[i].addListener('dragend',function(e) {
                    console.log(index_of_marker);
                    geocodeLatLngUpdateMarker(geocoder, map, this);
                    showDisplayRouteToEnd();
                    setTimeout(updateDOMPlaces,1000);
                    //geocode LatLng and Update Place in array
                    function geocodeLatLngUpdateMarker(geocoder, map ,marker) {
                        var lat = marker.getPosition().lat();
                        var lng = marker.getPosition().lng();
                        var latlng = { lat: parseFloat(lat),lng: parseFloat(lng) };
                        //geocoder from marker
                        geocoder.geocode({'location': latlng}, function(results, status) {
                          if (status === 'OK') {
                            if (results[1]) {
                              map.setZoom(11);
                              place = results[1].formatted_address;
                              //update place at index_of_marker
                              places.splice(index_of_marker,1,place);
                            } else {
                              window.alert('No results found');
                            }
                          } else {
                            window.alert('Geocoder failed due to: ' + status);
                          }
                        });
                    }
                    //update DOM Places
                    function updateDOMPlaces(){
                        for (var i = 0; i < (places.length-1); i++) {
                                $('#from'+i).val(places[i]);
                                $('#to'+i).val(places[i+1]);
                            }
                        $('#from'+(places.length -1)).val(places[places.length - 1]);
                        $('#to'+(places.length -1)).val(places[0]);
                    }
                });

                //Set Menu Context Event
                markers[i].addListener('rightclick',function rightclickMarker(e) {
                    //Show Context Menu for Marker
                    showContextMenu(e.latLng,1);
                    //if marker is first marker
                    if(index_of_marker == 0) {
                        $('#prev_marker').remove();
                    }
                    // if marker is last marker
                    if(index_of_marker == (markers.length - 1) && end_here == 0 && i != 0) {
                        $('#next_marker').remove();
                    }
                    //remove marker 
                    $('#remove_marker').click(function() {
                        // remove a plan in array and update DOM
                        if(index_of_marker == 0) {
                            $('#plan'+index_of_marker).remove();
                            for (var i = 1; i < plans.length; i++) {
                                $('#number_of_plan'+i).html("Plan "+i);
                                $('#from'+i).attr("id",'from'+(i-1));
                                $('#to'+i).attr("id",'to'+(i-1));
                                $('#time_start'+i).attr("id",'time_start'+(i-1));
                                $('#time_end'+i).attr("id",'time_end'+(i-1));
                                $('#vehicle'+i).attr("id",'vehicle'+(i-1));
                                $('#activity'+i).attr("id",'activity'+(i-1));
                            }
                            plans.splice(index_of_marker,1);
                        }else if(index_of_marker == (markers.length - 1)) {
                            $('#plan'+index_of_marker).remove();
                            plans[index_of_marker - 1].to = plans[index_of_marker].to;
                            plans.splice(index_of_marker,1);
                        }
                        else{
                            //marker is not first or last
                            var prev = index_of_marker - 1;
                            var next = index_of_marker + 1;
                            $('#plan'+index_of_marker).remove();
                            for (var i = next; i < plans.length; i++) {
                                $('#number_of_plan'+i).html("Plan "+i);
                                $('#from'+i).attr("id",'from'+(i-1));
                                $('#to'+i).attr("id",'to'+(i-1));
                                $('#time_start'+i).attr("id",'time_start'+(i-1));
                                $('#time_end'+i).attr("id",'time_end'+(i-1));
                                $('#vehicle'+i).attr("id",'vehicle'+(i-1));
                                $('#activity'+i).attr("id",'activity'+(i-1));
                            }
                            plans[index_of_marker - 1].to = plans[index_of_marker].to;
                            plans.splice(index_of_marker,1);
                        }
                        sum_plans --;

                        $('.contextmenu').remove();
                        markers[index_of_marker].setMap(null);
                        var marker_remove = markers.splice(index_of_marker,1);
                        places.splice(index_of_marker,1);
                        updatePlaces();
                        function updatePlaces() {
                            for (var i = 0; i < (places.length-1); i++) {
                                $('#from'+i).val(places[i]);
                                $('#to'+i).val(places[i+1]);
                            }
                            if(end_here == 1 ) {
                                $('#from'+(places.length -1)).val(places[places.length - 1]);
                                $('#to'+(places.length -1)).val(places[0]);
                            }
                        }
                        showDisplayRouteToEnd(); 
                    });//end remove marker

                    //add marker after a marker
                    $('#next_marker').click(function() {
                        var index = markers.findIndex(function(marker) {
                                return (e.latLng.lat() === marker.getPosition().lat()) && (e.latLng.lng() === marker.getPosition().lng());
                        });                        
                        console.log(index);
                        //create a marker after choosed marker
                        var lat = (markers[index].getPosition().lat() + markers[index+1].getPosition().lat())/2;
                        var lng = (markers[index].getPosition().lng() + markers[index+1].getPosition().lng())/2;
                        //set marker on map
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(lat,lng),
                            map: map,
                            draggable:true
                        }); 
                        //dragend for New marker (Last Marker)
                        marker.addListener('dragend',function(e) {
                            dragendMarker(e);
                        });
                        marker.addListener('rightclick',function(e) {
                            rightclickMarker(e);
                        });
                        //
                        updateDOMNextMarker();
                        markers.splice(index+1,0,marker);
                        addNewPlace(markers[index+1]);
                        showDisplayRouteToEnd();

                        function updateDOMNextMarker() {
                            //insert new plan after current plan
                            updateIdPlan();
                            newDOMPlan();
                            function newDOMPlan() {
                                var index_of_plan = index + 1;
                                console.log(index);
                                var new_plan_form = createFormPlan(index_of_plan);
                                $(new_plan_form).insertAfter('#plan'+index);
                                jQuery('.datetimepicker'+index_of_plan).datetimepicker();
                            }
                            function updateIdPlan() {
                                var next = index + 1;
                                var last = markers.length - 1;
                                for(var i = markers.length; i >= next ; i--) {
                                    console.log('change '+i);
                                    $('#number_of_plan'+i).html("Plan "+(i+2));
                                    $('#number_of_plan'+i).attr("id",'number_of_plan'+(i+1));
                                    $('#plan'+i).attr("id",'plan'+(i+1));
                                    $('#from'+i).attr("id",'from'+(i+1));
                                    $('#to'+i).attr("id",'to'+(i+1));
                                    $('#time_start'+i).attr("id",'time_start'+(i+1));
                                    $('#time_end'+i).attr("id",'time_end'+(i+1));
                                    $('#vehicle'+i).attr("id",'vehicle'+(i+1));
                                    $('#activity'+i).attr("id",'activity'+(i+1));
                                    $('.datetimepicker'+i).attr("class",'datetimepicker'+(i+1));
                                }
                            }
                        }
                        //
                        function addNewPlace(new_marker) {
                            geocodeLatLngUpdateMarker(geocoder, map, new_marker);
                            showDisplayRouteToEnd();
                            //geocode LatLng and Update Place in array
                            function geocodeLatLngUpdateMarker(geocoder, map ,marker) {
                                var lat = marker.getPosition().lat();
                                var lng = marker.getPosition().lng();
                                var latlng = { lat: parseFloat(lat),lng: parseFloat(lng) };
                                //geocoder from marker
                                geocoder.geocode({'location': latlng}, function(results, status) {
                                  if (status === 'OK') {
                                    if (results[1]) {
                                      map.setZoom(11);
                                      place = results[1].formatted_address;
                                      //update place at index_of_marker
                                      addNewPlaceArray();
                                      showPlan();
                                    } else {
                                       window.alert('No results found');
                                       place = "No results found";
                                       addNewPlaceArray();
                                       showPlan();
                                    }
                                  } else {
                                    window.alert('Geocoder failed due to: ' + status);
                                  }
                                });
                            }
                            function addNewPlaceArray() {
                                  places.splice(index+1,0,place);
                                  var plan = {};
                                  plan.from = places[index + 1];
                                  plan.to = places[index + 2];
                                  plans.splice(index+1,0,plan);
                                  plans[index].to = plans[index + 1].from;
                            }
                        }
                        function showPlan() {
                            $('#to'+index).val(plans[index + 1].from);
                            $('#from'+(index+1)).val(plans[index + 1].from);
                            $('#to'+(index+1)).val(plans[index + 1].to);
                        }                        
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
                });//end function show Menu Context
            }(index_of_marker)                
        }// end for Loop
    }
    function dragendMarker(e) {
        var index_of_marker = markers.findIndex(function(marker) {
                return (e.latLng.lat() === marker.getPosition().lat()) && (e.latLng.lng() === marker.getPosition().lng());
        });
        geocodeLatLngUpdateMarker(geocoder, map, e);
        showDisplayRouteToEnd();
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
                } else {
                  window.alert('No results found');
                }
              } else {
                window.alert('Geocoder failed due to: ' + status);
              }
            });
        }               
    }
    //draw Menu Contenxt
    function showContextMenu(caurrentLatLng, number) {
        var projection;
        var contextmenuDir;
        projection = map.getProjection();
        $('.contextmenu').remove();
        contextmenuDir = document.createElement("div");
        contextmenuDir.className  = 'contextmenu';
        switch(number){
            case 0: //Show Context Menu For Map
                contextmenuDir.innerHTML = '<a id="add_marker"><div class="context">Add Marker</div></a>';
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
    //draw Menu Contenxt
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
    //draw Menu Contenxt
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
    // var lat1 = $('#lat').val();
    // var lng1 = $('#lng').val();
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
          //$('#name_road').val('No results found');
        }
      } else {
        window.alert('Geocoder failed due to: ' + status);
        //$('#name_road').val('Geocoder failed ');
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

function createFormPlan(index_of_plan) {
        var plan_form = '<fieldset id="plan'+index_of_plan+'">' 
                    +'<legend id="number_of_plan'+index_of_plan+'" >Plan '+(index_of_plan + 1)+'</legend>'
                    +'<div>'
                        +'<div class="col-md-8">'
                            +'<div class="form-group col-md-3">'
                            +'<label for="from">From: </label>'
                            +'<input type="text" id="from'+index_of_plan+'">'
                            +'</div>'
                            +'<div class="form-group col-md-3 col-md-offset-4">'
                            +'<label for="to">To: </label>'
                            +'<input type="text" id="to'+index_of_plan+'">'
                            +'</div>'
                            +'<div class="form-group col-md-3">'
                            +'<label for="time_start">Time start: </label>'
                            +'<input type="text" class="datetimepicker'+index_of_plan+'" id="time_start'+index_of_plan+'" >'
                            +'</div>'
                            +'<div class="form-group col-md-3 col-md-offset-4">'
                            +'<label for="time_end">Time end: </label>'
                            +'<input type="text" class="datetimepicker'+index_of_plan+'" id="time_end'+index_of_plan+'">'
                            +'</div>'
                            +'<div class="form-group col-md-3">'
                            +'<label for="vehicle">Vehicle: </label>'
                            +'<input type="text" id="vehicle'+index_of_plan+'">'
                            +'</div>'
                            +'<div class="form-group col-md-3 col-md-offset-4">'
                            +'<label for="to">Activity: </label>'
                            +'<textarea id="activity'+index_of_plan+'"></textarea>'
                            +'</div>'
                        +'</div>'
                    +'</div>'
                    +'</fieldset>';
        return plan_form;                      
};

