
$(document).ready(function() {
	var bus = $('#bus').val();
	var dispatch = $('#dispatch').val();
	
	$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });//getting the token, for the POST type

	function retrieveSeats() {
		$.ajax({
			url: '/api/seats',
			type: 'POST',
			dataType: 'json',
			data: {bus: bus, dispatch: dispatch, _token: $('meta[name="csrf-token"]').attr('content')},
		})
		.done(function(data) {
			console.log(data);
			// Display the seats.
		})
		.fail(function(data) {
			console.log(data);
			alert('Cannot load bus seating arrangement. Please refresh the page.');
			console.log("Error: Cannot load data.");
		});
	}

	retrieveSeats(); //initialize the retrieve seat data.

	setInterval(function() {
		retrieveSeats();
	}, 1500); //refreshes the data for every 1.5 seconds.
	
});