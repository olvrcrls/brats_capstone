//pre-compiled source code to ES6 -- Oliver Carlos
import Vue from "vue";
Vue.use(require('vue-resource'));
let temp_seat_number;
let totalPassengers;
const emailRE =/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
let vm = new Vue({
	el: 'body',
	data: {
		'SeatProfile': {
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
			},

			'Payor': {
				'payorFirstName': '',
				'payorLastName': '',
				'payorMiddleName': '',
				'payorEmail': '',
				'payorContactNumber': '',

			},

			'passengers_left': 0,
			'toggle': true, //should be original val: true
			'agreed': false, //should be original val: false
			'choices': []
	}, //data binds
	methods:
	{
		initialize()
		{
			this.$http.post('/api/seats', { bus: this.SeatProfile.bus, dispatch: this.SeatProfile.dispatch, _token: this.SeatProfile._token} ).then(res => {
			  console.log(res.data);
			  this.SeatProfile.seats = res.data;
			  setTimeout(this.initialize(), 1500); //calls back the function every 1.5 seconds to refresh the seat status
			}).catch(err => {
			  console.log(err);
			}); //ajax request for retrieving the seats
		},

		reserve(event)
			{
				
				let indexSelected;
					indexSelected = this.SeatProfile.seats.indexOf(event.target.id);
					if(this.passengers_left > 0 && indexSelected <= -1) {
						event.target.src = '/images/selected_seat.png';
						this.SeatProfile.seats.push(event.target.id); //add the item to array
						this.passengers_left -= 1;

						//ADD an ajax request here to change the seat status on database
					}
					else if(indexSelected > -1) {
						event.target.src = '/images/available_seat.png';
						this.SeatProfile.seats.splice(indexSelected, 1); //remove the item from array
						this.passengers_left += 1;
						if (this.choices.length > 0) {
							indexSelected = this.choices.indexOf(event.target.id);
							this.choices.splice(indexSelected, 1); //remove the item in the choices

							//ADD an ajax request here to change the seat status on database
						}
					}
					else if(this.passengers_left == 0) {
						alert("You have already completed choosing the seats for your passengers.\n Please proceed to click Check Out button.");
					}

			},

			submit()
			{
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
	},//methods
	ready()
	{
		this.initialize(); //retrieves seats
	}
}); //Vue instance