# LaraMap
 ```html
 <!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complex Polylines</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous"></script>
        <script>
            var poly;
            var map;
            var lat ;
            var lng ;

        function initMap() 
        {
            map = new google.maps.Map(document.getElementById('map'), {
              zoom: 7,
              center: {lat: 33.6844, lng: 73.0479}  // Center the map on Chicago, USA.
            });

            poly = new google.maps.Polyline({
              strokeColor: '#000000',
              strokeOpacity: 1.0,
              strokeWeight: 3
            });
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
                    var latlng = new google.maps.LatLng(latval, lngval);
                    if(lngval != '')
                    {
                        renderMap(latlng);
                        createMarker(latlng)
                    }
                   
                });
                
            })
            .fail(function() {
                console.log("error");
            });
            // Map Render here
            function renderMap(latlng)
               /* var redCoords = [
                  {lat: latval, lng: lngval}
                ];
                poly = new google.maps.Polygon({
                  map: map,
                  paths: redCoords,
                  strokeColor: '#FF0000',
                  strokeOpacity: 0.8,
                  strokeWeight: 2,
                  fillColor: '#FF0000',
                  fillOpacity: 0.35,
                  draggable: true,
                  geodesic: true
                });*/
            {
                var path = poly.getPath();
                path.push(latlng);
            }

            function createMarker(latlng)
            {
                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
                 });
            }
            poly.setMap(map);

            // Add a listener for the click event
            map.addListener('click', addLatLng);
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
        function displayCoordinates(pnt) 
        {

          var lat = pnt.lat();
          //lat = lat.toFixed(4);
          var lng = pnt.lng();
          //lng = lng.toFixed(4);
          //console.log("Latitude: " + lat + "  Longitude: " + lng);
           $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
          $.ajax({
            url: 'geofence',
            type: 'POST',
            data: {lat: lat,lng: lng},
            })
            .done(function() {
                console.log("success");
            })
            .fail(function() {
                console.log("error");
            })
        }
        </script>
        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=&callback=initMap">
        </script>
  </body>
</html>
 ```
