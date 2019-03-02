# Geofencing Laravel Map

### Open controller in above file option

### Routes
```php
Route::get('/map', 'GeofenceController@index');
Route::post('/geofence', 'GeofenceController@store');
Route::get('/show-data', 'GeofenceController@show');
Route::post('/delete-polygon', 'GeofenceController@destroy');
Route::get('/delete-old-points', 'GeofenceController@delete_old_point');
Route::post('/session-set', 'GeofenceController@set_session');
Route::post('/update', 'GeofenceController@update');
```
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
    /*poly = new google.maps.Polyline({
        strokeOpacity: 1.0,
        strokeWeight: 3,
        strokeColor: '#FF0000',
        editable: true,
        geodesic: false
    });*/


    /* shaded polyline inside area*/
    poly = new google.maps.Polygon({
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        map: map,
        editable: true,
        geodesic: false
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
                createMarker(latval, lngval);
                
            }
        });
    /*  for (var i=0; i<res.length; i++)
        {
            marker_options.position = res[i];
            var point = new google.maps.Marker(marker_options);
          
            google.maps.event.addListener(point, "dragend", update_polygon_closure(poly, i));
        }*/
      
    })
    .fail(function() 
    {
        console.log("error");
    });

    poly.getPath().addListener('set_at', function(e)
    {

       /* delete_oldPoints();
        var points  = poly.getPath();
        points.forEach(function(element) 
        {
           var latlng = new google.maps.LatLng(element.lat(), element.lng());
           displayCoordinates(latlng);

        });*/

        var latitude = poly.getPath().getAt(e);
        var latlng = new google.maps.LatLng(latitude.lat(), latitude.lng());
        updateCoordinates(latlng);
        window.location.reload();
    });

  /* Map Render here */
    function renderMap(latval, lngval)
    {
        var latlng = new google.maps.LatLng(latval, lngval);
        var path = poly.getPath();
        path.push(latlng);
    }

    poly.setMap(map);

    poly.addListener('rightclick', function (event) {
        var lat = event.latLng.lat();
        var lng = event.latLng.lng();
        delete_polygon(lat);
        removeVertex(event.vertex)
    });
    /*  Get Latitude and longatude to click on poly*/
    poly.addListener('click', function (e) 
    {
        /*document.getElementById("lat").value = e.latLng.lat();
        document.getElementById("lng").value = e.latLng.lng();*/
        var lat  = e.latLng.lat();
        var lng = e.latLng.lng();
        $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        $.ajax({
            url: 'session-set',
            type: 'POST',
            data: {lat: lat, lng: lng},
        })
        .done(function() 
        {
            console.log("Latitude and Longitude has set in sesssion");
        })
        .fail(function() 
        {
            console.log("error");
        })
    });

   /* var place_polygon_path = poly.getPath()
    google.maps.event.addListener(place_polygon_path, 'set_at', polygonChanged);
    google.maps.event.addListener(place_polygon_path, 'insert_at', polygonChanged);

    function polygonChanged(event)
    {
        console.log('changed');
    }*/

  // Add a listener for the click event
    map.addListener('click', addLatLng);
  
}
/*                                                                           */
/*************************  End of InitMap Function **************************/
/*                                                                           */ 



/* Create Dragable function */
function update_polygon_closure(poly, i)
{

    return function(event)
    {
      /*var dragLat = event.latLng.lat();
      console.log(dragLat);*/
      var latitude = event.latLng.lat();
      console.log(latitude);
      displayCoordinates(event.latLng); 
    }
}
/*                                                                           */
/*************************  End of update_polygon_closure ********************/
/*                                                                           */

// Handles click events on a map, and adds a new point to the Polyline.
function addLatLng(event) 
{
    
    var path = poly.getPath();
    // Because path is an MVCArray, we can simply append a new coordinate
    // and it will automatically appear.
    path.push(event.latLng);

    // Add a new marker at the new plotted point on the polyline.
    var marker = new google.maps.Marker({
        position: event.latLng,
        //title: '#' + path.getLength(),
        icon: 'http://localhost/demo/public/flagLogo/buraq.png',
        map: map
    });
    // Store polygon lat and lng into database
    displayCoordinates(event.latLng);
}

/* Draw Marker */
function createMarker(latval, lngval)
{
    var latlng = new google.maps.LatLng(latval, lngval);
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        flat: true,
        title: 'Latitude',
        //draggable: true,
        raiseOnDrag: false,
        icon: 'http://localhost/demo/public/flagLogo/buraq.png'  
        //icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'
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

function updateCoordinates(pnt) 
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
      url: 'update',
      type: 'POST',
      data: {lat: lat,lng: lng},
    })
    .done(function() 
    {
        console.log("Successfully Updated");
    })
    .fail(function() 
    {
        console.log("error");
    })
}

function removeVertex(vertex) 
{
    var path = poly.getPath();
    path.removeAt(vertex);
}
function delete_polygon(lat) 
{
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: 'delete-polygon',
        type: 'POST',
        data: {lat: lat},
    })
    .done(function() 
    {
        console.log("Successfully Deleted");
    })
    .fail(function() 
    {
        console.log("error");
    })
    
}
function delete_oldPoints()
{
   $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });
   $.ajax({
      url: 'delete-old-points',
      type: 'GET',
   })
  .done(function() 
  {
      console.log("Successfully Deleted old points");
  })
  .fail(function() 
  {
      console.log("error");
  })
}
 ```
