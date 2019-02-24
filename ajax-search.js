$(document).ready(function(){
	$.ajaxSetup({
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});
	$('#country').click(function(){
		var country = $('#country').val();
		$.ajax({
			url: 'http://localhost:8000/api/search-city',
			type: 'POST',
			data: { _token: $('#signup-token').val(), country: country},
		})
		.done(function(match) 
		{
			$('#city').html(match);
		})
		.fail(function() 
		{
			console.log("error");
		})
		.always(function() 
		{
			console.log("complete");
		});
		
		
		
	});
});