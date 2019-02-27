```php
Route::get('/map', 'GeofenceController@index');
Route::post('/geofence', 'GeofenceController@store');
Route::get('/render-map', 'GeofenceController@render');
Route::get('/show-data', 'GeofenceController@show');


// Dragabel

Route::get('/drag', 'GeofenceController@drag');
```
```html


```

# Geofencing Laravel Map
 ```js
 var poly;
var map;

function initMap() 
{
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 7,
        draggable: true,
       // Center the map on Islamabad, Pakistan.
       center: {lat: 33.6844, lng: 73.0479},
   });
    /* Change Line color and opicity */
    poly = new google.maps.Polyline({
        strokeOpacity: 1.0,
        strokeWeight: 3,
        strokeColor: '#FF0000'
    });

    
    /* shaded polyline inside area*/
    poly = new google.maps.Polygon({
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        map: map,
        draggable: true,
        editable: true,
        geodesic: true
    });

    
    /* Marker Option */
     var marker_options = {
        map: map,
        flat: true,
        draggable: true,
        raiseOnDrag: false
    };


    /*google.maps.event.addListener(marker_options, "click", function (event) 
    {
        var latitude = event.latLng.lat();
        var longitude = event.latLng.lng();
        alert(latitude);
    });*/
    /* Ajax call to fetech data from database */
    $.ajax({
        url: 'show-data',
        type: 'GET',
    })
    .done(function(res) 
    {
       
        $.each(res, function(i, val)
        {
            var latval = val.lat;
            var lngval = val.lng;
            if(lngval != '')
            {
                renderMap(latval, lngval);
            }
        });
      /*  for (var i=0; i<res.length; i++)
        {
            marker_options.position = res[i];
            var point = new google.maps.Marker(marker_options);
            
            google.maps.event.addListener(point, "dragend", update_polygon_closure(poly, i));
        }*/
        google.maps.event.addListener(poly, 'dragend', function(e)
        {        
                console.log(e.latLng.lat());
        });

        
    })
    .fail(function() 
    {
        console.log("error");
    });
   
    /* Map Render here */
    function renderMap(latval, lngval)
    {
        var latlng = new google.maps.LatLng(latval, lngval);
        var path = poly.getPath();
        path.push(latlng);


    }

    poly.setMap(map);


    // Add a listener for the click event
    map.addListener('click', addLatLng);
    
}// End of InitMap Function



// click listner
  


/* Create Dragable function */
function update_polygon_closure(poly, i)
{

   // console.log(poly);
    return function(event)
    {
        /*var dragLat = event.latLng.lat();
        console.log(dragLat);*/
        var latitude = event.latLng.lat();
        console.log(latitude);
        displayCoordinates(event.latLng); 
    }
}


// Handles click events on a map, and adds a new point to the Polyline.
function addLatLng(event) 
{

    displayCoordinates(event.latLng);
    var path = poly.getPath();

    // Because path is an MVCArray, we can simply append a new coordinate
    // and it will automatically appear.
    path.push(event.latLng);

    // Add a new marker at the new plotted point on the polyline.
    var marker = new google.maps.Marker({
      position: event.latLng,
      title: '#' + path.getLength(),
      map: map
  });
}

/* Draw Marker */
function createMarker(latval, lngval)
{
    var latlng = new google.maps.LatLng(latval, lngval);
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        //flat: true,
        draggable: true,
        raiseOnDrag: false
        //icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
    });
}


/* Display Coordinates */
function displayCoordinates(pnt) 
{
    var lat = pnt.lat();
    //lat = lat.toFixed(4);
    var lng = pnt.lng();
    //lng = lng.toFixed(4);
    //console.log("Latitude: " + lat + "  Longitude: " + lng);

    /* Create Token to protect from CSRF */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /* Ajax Request to post data to database */
    $.ajax({
        url: 'geofence',
        type: 'POST',
        data: {lat: lat,lng: lng},
    })
    .done(function() 
    {
        console.log("success");
    })
    .fail(function() 
    {
        console.log("error");
    })
}
function enable() 
{
    return map.setOptions({
        draggable: true,
        zoomControl: true,
        scrollwheel: true,
        disableDoubleClickZoom: true
    });
}
 ```
