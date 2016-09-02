//pre-compiled source code to ES6 -- Oliver Carlos
import Vue from "vue";
//import BusSeat from "../vue/seats.vue";
Vue.use(require('vue-resource'));
let temp_seat_number;
let totalPassengers;
const emailRE =/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\8]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
let vm = new Vue({
	el: 'body', //scope is the whole body tag
	//components: { BusSeat },
	transition: 'fade',
	data: {
		/*
		 * Variables for counter of rows and bus specifications
		*/
		'ctrRow': 1,
		'leftColumn': null,
		'rightColumn': null,
		'totalRows': null,
		'rowPerColumn': null,
		'pickedSeats': [],

		/*
		 *	Variables for the seleced seats and the loaded seat data from ajax in the api URL
		*/

		'seats': [], // array of seat data that are loaded from the initialize method
		'SeatProfile': { //selected seat by the user for reservation
				'seats': [],
				'bustype': null,
				'bustype_id': null,
				'dispatch': null,
				'bus': null,
				'travel_date': null,
				'travel_time': null,
				'route': null,
				'route_id': null,
				'_token': $('meta[name="csrf-token"]').attr('content')
			}, // selected seats

		'Payor': { // payor's information
				'payorFirstName': '', // required
				'payorLastName': '', // required
				'payorMiddleName': '',
				'payorEmail': '', // required
				'payorContactNumber': '',

			},

			'passengers_left': 0, // determines of how many seats will be selected by the user
			'toggle': true, //should be original val: true
			'agreed': false, //should be original val: false
			'choices': []
	}, //data binds

	methods:
	{
		reserve(event)
			{
				let indexSelected;
				let seatNumber;
				let seatStatus;
					indexSelected = this.SeatProfile.seats.indexOf(event.target.id)
					seatNumber = event.target.id
					// AJAX request checks if the seat is already queued on the server first
					this.$http.post('/api/seats/check',
						{seat_number: seatNumber, bus: this.SeatProfile.bus, dispatch: this.SeatProfile.dispatch,
						 _token: this.SeatProfile._token
					}).then(response => {
						seatStatus = response.data
						if (seatStatus == 'Open' || seatStatus == 'Available' || seatStatus == 'open' || seatStatus == 'available')
						{
							if (indexSelected <= -1 && this.passengers_left > 0)
							{
								this.$http.post('/api/seats/update/tentative', {
									bus: this.SeatProfile.bus,
									dispatch: this.SeatProfile.dispatch,
									seat_number: seatNumber,
									_method: 'PUT',
									_token: this.SeatProfile._token
								}).then(response => {
									//if success
									event.target.src = 'images/tentative_seat.png'
									this.SeatProfile.seats.push(seatNumber)
									this.passengers_left -= 1
								}).catch(error=>{
									//if error
									console.log('Cannot update the seat status.')
								}) // http ajax post request 
							} //checks if already included in the seats list
							else if (this.passengers_left == 0)
							{
								alert('You have completed your number of passengers for their seat choices.\n Please press the PROCEED button')
							}
						}// checks if the seat status is available or open for reservation
						else if (indexSelected >= 0 && (seatStatus == 'Tentative' || seatStatus == 'tentative'))
						{
							this.unreserve(event)
						}
						else if (this.passengers_left == 0)
						{
							alert('You have completed your number of passengers for their seat choices.\n Please press the PROCEED button')
						}
						else
						{
							event.target.src = 'images/selected_seat.png'
							alert("The seat is no longer available or open. \n Some another user must have picked that first before you.")
						}
					}).catch(error => {
						console.log("Cannot load seat status from server.")
					})
					
			},

			unreserve(event)
			{
				let indexSelected
				let seatNumber
					indexSelected = this.SeatProfile.seats.indexOf(event.target.id)
					seatNumber = event.target.id
				if (indexSelected > -1)
				{
					if (this.choices.length >= 0)
					{
						this.$http.post('/api/seats/update/unqueue', {
							bus: this.SeatProfile.bus,
							dispatch: this.SeatProfile.dispatch,
							seat_number: seatNumber,
							_method: 'PUT',
							_token: this.SeatProfile._token
						}).then(response => {
							//if success
							event.target.src = 'images/available_seat.png'
							this.SeatProfile.seats.splice(indexSelected, 1)
							this.passengers_left += 1
						}).catch(error => {
							console.log(error)
							alert('Cannot undo selection of selected seat')
						})
					} // double checks if the choices are not less than or equal to 0
				} //checks if the seat is already chosen, if chosen, then unqueue
			}, // checks if the seat is already selected
			

			submit()
			{
				/*
				 * Validation before
				 * Proceeding to input the Payor's information and Passengers Information
				*/
				if(this.passengers_left == 0 && this.SeatProfile.seats != null) {
					//submits the form 
						if(this.passengers_left == 0 && this.toggle == true) {

							for(let i = 0; i < this.SeatProfile.seats.length; i++) {
								this.choices.push(this.SeatProfile.seats[i]);
							}

							this.$http.post('/api/seats/update/queue', {
								bus: this.SeatProfile.bus,
								dispatch: this.SeatProfile.dispatch,
								seats: this.choices,
								_method: 'PUT',
								_token: this.SeatProfile._token
							}).then(response => {
								alert('Now, please input some information for the following forms.');
								this.toggle = false;
							
							}).catch(error => {
								console.log(error)
							})
						}
							
				} else {
					alert('Complete your seating arrangements first for all of your passengers.');
				}
			},

			agree()
			{
				/*
				 * Validates the user's input in the payor's form
				*/
				if (this.Payor.payorFirstName != '' && this.Payor.payorLastName != '' && emailRE.test(this.Payor.payorEmail)) {
					this.agreed = true;
				} else {
					alert("Please properly fill up the Payor`s Information Form.");
				}
			},
			
			returnSeatingArrange()
			{
				this.$http.post('/api/seats/update/all/unqueue', {
					_method: 'PUT',
					_token: this.SeatProfile._token,
					bus: this.SeatProfile.bus,
					dispatch: this.SeatProfile.dispatch,
					seats: this.choices
				}).then(response => {
					this.toggle = true;	
				}).catch(error => {
					console.log(error)
				})
				
			},

			returnPayor()
			{
				this.agreed = false;
			},

			verifyFields(event)
			{	// verify all the seat choices chosen for each passengers
				// seat choices must not be duplicated.
				document.checkOutForm.submit();
			},

			optionSelect(event)
			{
				console.log(event.target.id);
			}
	},//methods

	Beforedestroy()
	{
		// this eliminates the selected seats when the window is closed or refreshed

		if (this.choices.length > 0)
		{
			this.$http.post('/api/seats/update/all/available', {
				'bus': this.SeatProfile.bus,
				'seats': this.choices,
				'dispatch': this.SeatProfile.dispatch,
				'_token': this.SeatProfile._token,
				'_method': 'PUT'
			}).then(response => {
				// if success
				console.log('Selected seats are now updated to "Available"')
			}).catch(error => {
				// if failure
				console.log('Unable to change or update seat statuses')
				console.log(error)
			})
		} // checks if there are selected seats first
	}
}) //Vue instance
/* TO DO : Transition effecs, etc etc */
Vue.transition('fade', {
	css: false,
	enter(el, done)
	{
		$(el)
			.css('opacity', 0)
			.animate({opacity: 1}, 1500, done)
	},

	enterCancelled(el)
	{
		$(el).stop()
	},

	leave(el, done)
	{
		$(el).animate({opacity: 0}, 1500, done)
	},

	leaveCancelled(el)
	{
		$(el).stop()
	}
})