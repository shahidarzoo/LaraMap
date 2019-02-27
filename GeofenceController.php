<?php

namespace App\Http\Controllers;

use App\Geofence;
use Illuminate\Http\Request;

class GeofenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('map.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Geofence::create([
            'lat'=> $request->lat,
            'lng'=> $request->lng,
        ]);
        return redirect('/map');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Geofence  $geofence
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $data = Geofence::select('lat', 'lng')->get();
        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Geofence  $geofence
     * @return \Illuminate\Http\Response
     */
    public function drag()
    {
        return view('map.mapdrag');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Geofence  $geofence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Geofence $geofence)
    {
        //
    }
}
