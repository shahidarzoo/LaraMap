<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Car;
use App\Location;

class CarController extends Controller
{

	public function index()
	{
		$countries = Location::pluck('country', 'country');
		 return view('maps.index', compact('countries'));
	}
   public function search(Request $request)
   {
   		$lat = $request->lat;
   		$lng = $request->lng;

   		$cars = Car::whereBetween('lat', [$lat-0.1, $lat+0.1])
   						->whereBetween('lng', [$lng-0.1, $lng+0.1])
   						->get();
   		return $cars;
   }

   public function searchCity(Request $request)
   {
   		$country = $request->country;
   		$cities = Location::where('country', $country)->pluck('city', 'city');
  		//dd($cities);
   		return view('maps.ajax-result', compact('cities'));
   }
}
