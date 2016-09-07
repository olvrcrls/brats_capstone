@extends('layout')
@section('content')
	<div class="brats-border">
		<h2>
			<b>Reviewing your Transaction</b>
		</h2>
		<hr> &nbsp;
		<form method="POST" action="{{ url('/transaction/complete') }}" accept-charset="utf-8"
			  name="finalCheckoutForm" id="finalCheckoutForm" @submit.prevent
		>
			{{ csrf_field() }}
			{{ method_field('POST') }}
			
			<div class="row col s12 col m12" style="margin-left: 10%;">
				<div class="card-panel red darken-1 white-text col s5 col m5 z-depth-3 hoverable">
					<h4 class="flow-text"><b>Payor`s Information <i class="fa fa-user"></i></b></h4>
					<hr>
					<br>

					<span class="checkout-text">
						Full Name : <b>{{ $request->OnlineCustomer_FirstName.' '. $request->OnlineCustomer_MiddleName.' '.$request->OnlineCustomer_LastName }}</b>
						<input type="hidden" name="OnlineCustomer_FirstName" value="{{ $request->OnlineCustomer_FirstName }}"
							   v-model="OnlineCustomer.firstName"
						>
						<input type="hidden" name="OnlineCustomer_LastName" value="{{ $request->OnlineCustomer_LastName }}"
								v-model="OnlineCustomer.lastName"
						>
						<input type="hidden" name="OnlineCustomer_MiddleName" value="{{ $request->OnlineCustomer_MiddleName }}"
								v-model="OnlineCustomer.middleName"
						>
					</span> <br><br>
					<span class="checkout-text">
						Contact Number : <b>{{ $request->OnlineCustomer_ContactNumber }}</b>
						<input type="hidden" name="OnlineCustomer_ContactNumber" value="{{ $request->OnlineCustomer_ContactNumber }}">
					</span> <br><br>
					<span class="checkout-text">
						E-mail Address : <b>{{ $request->OnlineCustomer_Email }}</b>
						<input type="hidden" name="OnlineCustomer_Email" value="{{ $request->OnlineCustomer_Email }}"
								v-model="OnlineCustomer.email"
						>
					</span> <br><br>
					<span class="checkout-text">
						Date of Reservation : <b>{{ date_format(date_create($request->OnlineCustomer_DateOfReservation), 'm/d/Y') }}</b>
						<input type="hidden" name="OnlineCustomer_DateOfReservation" value="{{ $request->OnlineCustomer_DateOfReservation }}">
					</span><br><br>&nbsp;
				</div>
				<div class="card-panel z-depth-3 green white-text col m5 col s5 hoverable"
				style="margin-left:20px;">
					<h4 class="flow-text"><b>Trip`s Information <i class="fa fa-bus"></i></b></h4>
					<hr>
					<br>
					<span class="checkout-alter-text">
						Route : <b>{{ $request->route }}</b>
						<input type="hidden" name="TravelDispatch_Id" value="{{ $request->dispatch }}">
						<input type="hidden" name="Route_Id" value="{{ $request->route_id }}">
					</span> <br><br>
					<span class="checkout-alter-text">
						Bus Number : <b>{{ $request->bus }}</b>
					</span> <br><br>
					<span class="checkout-alter-text">
						Bus Type : <b>{{ $request->bustype }}</b>
					</span> <br><br>
					<span class="checkout-alter-text">
						Date of Departure : <b>{{ date_format(date_create($request->travel_date), 'm/d/Y') }}</b>
					</span> <br><br>
					<span class="checkout-alter-text">
						Time of Departure : <b>{{ date_format(date_create($request->travel_time), 'h:i:s a') }}</b>
					</span> <br>
					<span class="checkout-alter-text">
						Number of Passengers : <b>{{ $request->totalPassengers }} Passenger(s)</b>
					</span> <br><br>
				</div>
			</div>
			<div class="row col s12 col m12">
				<div class="card-panel blue darken-1 white-text z-depth-3 hoverable col s4 col m4"
						style="margin-left: 10%; width: 38%;" 
				>
					<h4 class="flow-text white-text center"><b>NOTICE <i class="fa fa-info-circle"></i></b></h4>
					<hr>
					<ul class="brats-border">
						<li>
							<p><b>A.</b> The <b>Payor</b> can pay either to the bank and deposit to the bus company`s account or directly pay to the terminal cashier.</p>
						</li>
						<li>
							<p><b>B.</b> The auto-generated voucher must be printed and be presented upon paying.</p>
						</li>
						<li>
							<p><b>C.</b> Be at the terminal for atleast <b>30 minutes</b> before the departure time. Your reserved seats will be given to those who can accomodate, if the reserved passengers will be late.</p>
						</li>
						<li>
							<p><b>D.</b> - Reserved Tickets that are already fully paid / half paid can only be refunded within <b>2 days from the date of the ticket(s) reservation</b>.
							<br><br>
						Percentage of refunded money as every day lapse. <br>
						<ul>
							<li>
								> <b>100%</b> refund money (excluding online service fee) when refunded less than 24 hours from the reservation date.
							</li>
							<li>
								> <b>80%</b> refund money (excluding online service fee) when refunded 1 day after the reservation date.
							</li>
							<li>
								> <b>75%</b> refund money (excluding online service fee) when refunded 2 days after the reservation date.
							</li>
						</ul></p>
						</li>
						<li>
							<p><b>E.</b> Each online reservation transactions have <i class="fa fa-rub"></i>{{ $onlineFee }} additional to the <b>Total Transaction Price</b> in accordance for the fee of online services and maintenance.</p>
						</li>
					</ul>
				</div>
				<div class="card-panel yellow darken-2 white-text text-darken-1 z-depth-3 hoverable col s5 col m5"
						style=" margin-left: 10px; width: 38%;" 
				>
					<h4 class="flow-text"><b>Passenger`s Information <i class="fa fa-users"></i></b></h4>
					<hr>
					<input type="hidden" name="totalPassengers" value="{{ $request->totalPassengers }}">
					@for($i = 0; $i < $request->totalPassengers; $i++)
					<br>
						<span class="flow-text"><b>Passenger #{{ $i+1 }}</b></span>
						<br>&nbsp;	
						<span>
							Seat Number : <b>{{ $request->passengerSeat[$i] }}</b>
							<input type="hidden" name="BusSeat_Number[]" value="{{ $request->passengerSeat[$i] }}">
						</span> <br>&nbsp;
						<span>
							Fullname : <b>{{ $request->passengerFirstName[$i].' '.$request->passengerMiddleName[$i].' '.$request->passengerLastName[$i] }}</b>
							<input type="hidden" name="Passenger_FirstName[]" value="{{ $request->passengerFirstName[$i] }}">
							<input type="hidden" name="Passenger_LastName[]" value="{{ $request->passengerLastName[$i] }}">
							<input type="hidden" name="Passenger_MiddleName[]" value="{{ $request->passengerMiddleName[$i] }}">
						</span> <br>&nbsp;
						<span>
							Gender : <b>{{ $request->passengerGender[$i] }}</b>
							<input type="hidden" name="Passenger_Gender[]" value="{{ $request->passengerGender[$i] }}">
						</span> <br>&nbsp;
						<span>
							Age : <b>{{ $request->passengerAge[$i] }} yrs. old</b>
							<input type="hidden" name="Passenger_Age[]" value="{{ $request->passengerGender[$i] }}">
						</span> <br>&nbsp;
						<span>
							Contact : <b>{{ $request->passengerContactNumber[$i] }}</b>
							<input type="hidden" name="Passenger_ContactNumber[]" value="{{ $request->passengerContactNumber[$i] }}">
						</span> <br>&nbsp;
						<span>
							Destination : <b>{{ $request->route.' ('.$passengers[$i]->destinationName[0]->RoutePathWays_Place.')' }}</b>
							<input type="hidden" name="RoutePathWays_Id[]" value="{{ $passengers[$i]->destinationId }}">
						</span><br>&nbsp;
						<span>
							Fare Price : <b><i class="fa fa-rub"></i> {{ $passengers[$i]->fare }}</b>
							<input type="hidden" name="fares[]" value="{{ (float)$passengers[$i]->fare }}" v-model="Passenger.fares[]">
						</span><br>&nbsp;
							<?php $totalFarePrice += $passengers[$i]->fare ; ?>
					@endfor
					<br><br>&nbsp;
					<span>
						Online Service Fee : <b><i class="fa fa-rub"></i> {{ $onlineFee }} </b>
					</span>
					<hr><br>
					<span class="flow-text">
						<b>Total Transaction Fee : <i class="fa fa-rub"></i> {{ $totalFarePrice + $onlineFee }}</b>
						<input type="hidden" name="Purchase_Date" value="{{ date('Y-m-d') }}">
					</span>
					<br>&nbsp;
				</div>
				<div class="row col s12 col m12">
					<div class="col s6 col m6 right">
					<br>&nbsp;
						<button class="btn btn-large col m6 offset-m3
								 green darken-1 
								 waves-effect waves-light z-depth-2" 
								 type="button" id="btnSubmit"
						>
							<b>PROCEED <i class="fa fa-check"></i></b>
						</button>
					</div>
				</div>
			</div>
		</form>
	</div>
@stop
@section('footer')
	<script type="text/javascript" src="/js/compiled/checkout_es6.js"></script>
@stop