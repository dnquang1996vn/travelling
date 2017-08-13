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
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer({
      draggable: false,
      map: map,
    });
    var geocoder = new google.maps.Geocoder;
    var infowindow = new google.maps.InfoWindow;
    //update all marker,places and plans
    updateFromDB();

    function updateFromDB(){
        updateMarkersFromDB();   //update Markers Array
        hiddenMarkers();
        showDisplayRouteToEnd();
    }

    function updateMarkersFromDB() {
        for(var i = 0; i < sum_plans; i++){
            var latLng = {lat: parseFloat(locations[i][0]),lng: parseFloat(locations[i][1])};
            marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
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
    function hiddenMarkers() {
        for(var i = 0; i< markers.length; i++) {
            markers[i].setMap(null);
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
    google.maps.event.addListener(map, 'click', function() {
        directionsDisplay.setMap(null);
        directionsDisplay.setOptions({
          polylineOptions: {
            strokeColor: 'red'
          }
        });
    directionsDisplay.setMap(map);
  });

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

