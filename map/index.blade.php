<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complex Polylines</title>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 500px;
        width: 90%;
        border: 2px solid #ccc;
      }
      .heading{
        text-align: center;
      }
    </style>
  </head>
  <body>
    <div class="container">
        <h1 class="heading text-primary">Google Map</h1>
        <div id="map">
          <div id="delete"></div>
        </div>
    </div>
    
    <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous"></script>
        <script type="text/javascript" src="{{asset('public/js/script.js')}}"></script>
        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyACLTLeNgkCI8smLndudh4LmL_P2NMWp5g&callback=initMap&&libraries=drawing">
        </script>
  </body>
</html>