@extends('layouts.master')

@section('content')

<p class="map">Lara Map</p>
<div class="row">
    <div class="col-md-3">
        {!! Form::open(['url' => 'foo/bar']) !!}
        @csrf
			<div class="form-group">
				{!! Form::label('country', 'Country', ['class' => 'awesome']) !!}
				
				{!! Form::select('country', $countries, null, ['class' => 'form-control', 'id' => 'country']); !!}


				
			</div>
			<div id="city" class="form-group">
				
			</div>
			<div class="form-group">
				{!! Form::button('Search',array('class'=>'btn btn-success','id'=>'search')) !!}
			</div>
		{!! Form::close() !!}
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