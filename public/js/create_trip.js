
// In the following example, markers appear when the user clicks on the map.
// Each marker is labeled with a single alphabetical character.
var marker;
var markers = [];
var waypts = [];
var plans = [];
var places = [];
var count_plan = -1;    //count_plan plan0,plan1,plan2
var end_here = 0;
var new_trip = {};

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
            placeMarkerAndAddMarker(e.latLng,map);
            geocodeLatLngMarker(geocoder, map, markers[markers.length - 1]);
            //when has more than 2 marker ---> show Display Route 
            showDisplayRoute();

            //dragend for New marker (Last Marker)
            markers[markers.length - 1].addListener('dragend',function(event) {
                dragendMarker(event);
                setTimeout(updateDOMPlaces,1000);
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
                if(index_of_marker == (markers.length - 1) && end_here == 0 && index_of_marker != 0) {
                    $('#next_marker').remove();
                    $('<a id="end_here"><div class="context">End Here</div></a>').appendTo('.contextmenu');
                }

                //remove marker 
                $('#remove_marker').click(function() {
                    $('.contextmenu').remove();
                    // remove a plan in array and update DOM
                    if(index_of_marker == 0) {
                        $('#plan'+index_of_marker).remove();
                        //decrement all plan_id from plan_id = 1
                        updateDOMPlans(1);
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
                        $('#plan'+index_of_marker).remove();
                        //decrement all plan_id from plan_id = next
                        updateDOMPlans(index_of_marker + 1);
                        plans[index_of_marker - 1].to = plans[index_of_marker].to;
                        plans.splice(index_of_marker,1);
                    }

                    console.log('count_plan'+count_plan);
                    count_plan --;
                    
                    markers[index_of_marker].setMap(null);
                    var marker_remove = markers.splice(index_of_marker,1);
                    places.splice(index_of_marker,1);
                    //update place in Plan
                    updateDOMPlaces();
                    //if not click end here
                    if(end_here == 0) {
                        showDisplayRoute(); 
                    }else {
                        showDisplayRouteToEnd(); 
                    }
                }); //end remove click

                //Click end here
                $('#end_here').click(function() {
                    $('.contextmenu').remove();
                    end_here = 1;
                    count_plan++;
                    console.log(count_plan);
                    //create form with index = count_plan
                    var plan_form = createFormPlan(count_plan);
                    $('#plan_form').append(plan_form);
                    $('<button name="finish_trip" id="finish_trip">Finish Trip</button>').appendTo('#plan_form');
                    showDisplayRouteToEnd();
                    //insert plan to Plans Array and update #from first plan and #to last plan
                    addLastPlan();
                    jQuery('.datetimepicker'+count_plan).datetimepicker();
                    //add new nicEditor
                    new nicEditor().panelInstance('activity'+count_plan);
                    //set event for Finish Trip Button
                    $('#finish_trip').click(function() {
                        new_trip.name        = $('#trip_name').val();
                        new_trip.time_start  = $('#time_start').val();
                        new_trip.time_end    = $('#time_end').val();
                        new_trip.description = $('#description').parent().find('div.nicEdit-main').html();
                        // save all input to Plan Object Array
                        savePlansPreSending();
                        console.log(plans);

                        //
                        $('#plans').val(JSON.stringify(plans));
                        $('#new_trip').val(JSON.stringify(new_trip));
                        var data = new FormData($('#form_upload_trip_cover')[0]);
                        //sending data with ajax
                        $.ajaxSetup({
                            headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'accepts': 'application/json',
                            }
                        });

                        $.ajax({
                            url: '/create_trip',
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
                });

            });     //End Show Context for Marker
            /*** 
            ***Update DOM
            ***/
            //create a plan
            var plan = {};
            var flag = 0;
            
            function showPlan() {
                var plan = {};
                plan.from = places[markers.length -2];
                plan.to = places[markers.length - 1];
                $('#from'+count_plan).val(plan.from);
                $('#to'+count_plan).val(plan.to);
                plans.push(plan);
                console.log(plans);
                //set date time picker for last plan
                jQuery('.datetimepicker'+count_plan).datetimepicker();
            }

            //if has more than 1 marker
            if( markers.length > 1) {
                count_plan++;
                $(document).ready(function() {
                    var plan_form = createFormPlan(count_plan);
                    $('#plan_form').append(plan_form);
                    new nicEditor().panelInstance('activity'+plans.length);
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
                      place = "No results found";
                      places.push(place);
                    }
                  } else {
                    window.alert('Geocoder failed due to: ' + status);
                  }
                });
            }

        }); // End click Add Marker function
        
    });

    //1
    //var i = 0 ;
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

function createFormPlan(count_plan) {
        var plan_form = '<fieldset id="plan'+count_plan+'">' 
                    +'<legend id="number_of_plan'+count_plan+'" >Plan '+(count_plan + 1)+'</legend>'
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

function savePlansPreSending() {
    for (var i = 0; i < plans.length; i++) {
        plans[i].from       = $('#from'+i).val();
        plans[i].to         = $('#to'+i).val();
        plans[i].time_start = $('#time_start'+i).val();
        plans[i].time_end   = $('#time_end'+i).val();
        plans[i].vehicle    = $('#vehicle'+i).val();
        plans[i].activity  = $('#activity'+i).parent().find('div.nicEdit-main').html();
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

function addLastPlan() {
    //update Plans Array
    var plan = {};
    plan.from = places[markers.length -1];
    plan.to = places[0];
    $('#from'+count_plan).val(plan.from);
    $('#to'+count_plan).val(plan.to);
    plans.push(plan);
}

function updateDOMPlans(position) {
    for (var i = position; i < plans.length; i++) {
        $('#plan'+i).attr("id",'plan'+(i-1));
        $('#number_of_plan'+i).attr("id",'number_of_plan'+(i-1));
        $('#number_of_plan'+(i-1)).html("Plan "+i);
        $('#from'+i).attr("id",'from'+(i-1));
        $('#to'+i).attr("id",'to'+(i-1));
        $('#time_start'+i).attr("id",'time_start'+(i-1));
        $('#time_end'+i).attr("id",'time_end'+(i-1));
        $('#vehicle'+i).attr("id",'vehicle'+(i-1));
        $('#activity'+i).attr("id",'activity'+(i-1));
    }
}

function updateDOMPlaces(){
    for (var i = 0; i < (places.length-1); i++) {
            $('#from'+i).val(places[i]);
            $('#to'+i).val(places[i+1]);
        }
    if(end_here == 1 ) {
            $('#from'+(places.length -1)).val(places[places.length - 1]);
            $('#to'+(places.length -1)).val(places[0]);
    }
}

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
