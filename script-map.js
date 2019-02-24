var map;
var myLatLng;
$(document).ready(function(){
	geoLocationInit();
	function geoLocationInit()
	{
		if(navigator.geolocation)
		{
			navigator.geolocation.getCurrentPosition(success, fail);
		}
		else
		{
			alert('Browser not Supported'); 
		}
	}

	function success(position)
	{
		var lat = position.coords.latitude;
		var lng = position.coords.longitude;
		console.log(lat, lng);

		myLatLng = new google.maps.LatLng(lat, lng);
		createMap(myLatLng);
		//var type  = "school";
		//nearBySearch(myLatLng, type);

		searchCar(lat, lng);
	}

	function fail()
	{
		alert('Someting went wrong');
	}

	function createMap(myLatLng)
	{
		map = new google.maps.Map(document.getElementById('map'), {
          center: myLatLng,
          zoom: 12
        });
        var marker = new google.maps.Marker({
		    position: myLatLng,
		    map: map
		 });
	}
	function createMarker(latlng, icn, name)
	{
		var marker = new google.maps.Marker({
		    position: latlng,
		    map: map,
		    icon: icn,
		    title: name
		 });
	}
		

	/*function nearBySearch(myLatLng, type)
	{
		var request = {
	    location: myLatLng,
	    radius: '1500',
	    type: [type]
	  };

		service = new google.maps.places.PlacesService(map);
		service.nearbySearch(request, callback);


		function callback(results, status) 
		{
			//console.log(results);
			if (status == google.maps.places.PlacesServiceStatus.OK) 
			{
			    for (var i = 0; i < results.length; i++) 
			    {
				    var place = results[i];
				    latlng= place.geometry.location;
				    icn= 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';
				    //icn= place.icon;
				    name= place.name;

				    createMarker(latlng, icn, name);
			    }
			}
		}
	}*/

	function searchCar(lat, lng)
	{
		$.post('http://localhost:8000/api/search-car', {lat:lat, lng:lng}, function(match){
			$.each(match, function(i, val){
				var getlatvalue = val.lat;
				var getlngvalue = val.lng;
				//console.log(getlatvalue);
				var getname = val.name;
				var geticon = 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';

				var GetLatLng = new google.maps.LatLng(getlatvalue, getlngvalue);
				createMarker(GetLatLng, geticon, getname)
			});
		});
		
	}
});