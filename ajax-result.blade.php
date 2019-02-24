{!! Form::label('city', 'City', ['class' => 'awesome']) !!}

{!! Form::select('city', $cities, null, ['class' => 'form-control', 'id' => 'city']); !!}