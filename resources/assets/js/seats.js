//pre-compiled source code to ES6 -- Oliver Carlos
import Vue from "vue";
//import BusSeat from "../vue/seats.vue";
Vue.use(require('vue-resource'));
let temp_seat_number;
let totalPassengers;
const emailRE =/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\8]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
let vm = new Vue({
	el: 'body',
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
		initialize()
		{
			this.$http.post('/api/seats', { bus: this.SeatProfile.bus, dispatch: this.SeatProfile.dispatch, _token: this.SeatProfile._token} ).then(response => {
			 this.seats = response.data;
			 setInterval(this.initialize(), 500); //calls back the function every .5 second to refresh the seat status
			 // console.log(this.seats[0].bus_seat_statuses.BusSeatStatus_Name); //for testing.
			 // alternative setTimeout(this.initialize(), 1500);
			 //console.log(response.data); //to extract data ( response.data[index_number].property )
			 //console.log(this.seats[0].BusSeat_Number); // to extract data ( this.seats[index_number].property )
			}).catch(error => {
			  console.log('Cannot retrieve data' + error.data);
			}); //ajax request for retrieving the seats
		},
		reserve(event)
			{
				let indexSelected;
				let seatId;
					indexSelected = this.SeatProfile.seats.indexOf(event.target.id)
					seatId = event.target.name;
					// seatStatus = event.target.className;
					if (this.passengers_left > 0 && indexSelected <= -1) { //checks if the seat is not yet reserved/queued and if there is slots left to pick
						event.target.src = '/images/tentative_seat.png';
						this.SeatProfile.seats.push(event.target.id); //add the item to array
						this.passengers_left -= 1;
						//ADD an ajax request here to change the seat status on database
						// this.$http.post('/api/seats/update/queue', {seat_id: seatId, _method: 'PUT', _token: this.SeatProfile._token}).then(response =>  {
						// 	// change the seat status of the current bus seat to `Queue` or `On Queue`
						// 	console.log(event.target.id + ' has changed status.')
						// }).catch(error => {
						// 	console.log(event.target.id + ' cannot change status.')
						// }); //$this.http.post request
					}
					else if (indexSelected > -1) { // checks if the seat is already on queue
						event.target.src = '/images/available_seat.png';
						this.SeatProfile.seats.splice(indexSelected, 1); //remove the item from array
						this.passengers_left += 1;
						if (this.choices.length > 0) {
							indexSelected = this.choices.indexOf(event.target.id);
							this.choices.splice(indexSelected, 1); //remove the item in the choices
							//ADD an ajax request here to change the seat status on database
							// this.$http.post('/api/seats/update/unqueue', {seat_id: seatId, _method: 'PUT', _token: this.SeatProfile._token}).then(response =>  {
							// 	console.log(event.target.id + ' has changed status. Queue')
							// // change the seat status of the current bus seat to `Available` or `Open`
							// }).catch(error => {
							// 	console.log(event.target.id + ' cannot change status.')
							// }); //http post request
						}
					}
					else if(this.passengers_left == 0) {
						alert("You have already completed choosing the seats for your passengers.\n Please proceed to click Check Out button.");
					}
			},

			submit()
			{
				/*
				 * Validation before
				 * Proceeding to input the Payor's information and Passengers Information
				*/
				if(this.passengers_left == 0 && this.SeatProfile.seats != null) {
					//submits the form 
						if(this.passengers_left == 0 && this.toggle == true) {
							alert('Now, please input some information for the following forms.');
							this.toggle = false;
							for(let i = 0; i < this.SeatProfile.seats.length; i++) {
								this.choices.push(this.SeatProfile.seats[i]);
							}
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
				this.toggle = true;
			},

			returnPayor()
			{
				this.agreed = false;
			},

			verifyFields(event)
			{	// verify all the seat choices chosen for each passengers
				// seat choices must not be duplicated.
				document.checkOutForm.submit();
			}
	},//methods
	ready()
	{
		this.initialize(); //retrieves seats
	}
}) //Vue instance

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