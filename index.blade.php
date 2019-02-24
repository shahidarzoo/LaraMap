@extends('layouts.master')

@section('content')

<p class="map">Lara Map</p>
<div class="row">
    <div class="col-md-3">
        {!! Form::open(['url' => '/get-location', 'id' => 'searchCar']) !!}
        @csrf
        <div class="form-group">
        	{!! Form::selectMonth('month', null, ['class' => 'form-control', 'id' => 'month']); !!}
        </div>
			<div class="form-group">
				{!! Form::label('country', 'Country', ['class' => 'awesome']) !!}
				
				{!! Form::select('country', $countries, null, ['class' => 'form-control', 'id' => 'country']); !!}


				
			</div>
			<div id="city" class="form-group">
				
			</div>
			<div class="form-group">
				{!! Form::submit('Search',array('class'=>'btn btn-success')) !!}
			</div>
		{!! Form::close() !!}
		<div>
			<h4>Name Of Cities</h4>
			<hr>
			<ul id="cityData">
				
			</ul>
		</div>
    </div>
    <div class="col-md-9">
        <div id="map">
	
		</div>
    </div>
</div>
@endsection
@section('js')
<script src="{{asset('/js/script-map.js')}}"></script>

@endsection