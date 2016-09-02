@extends('layout')
@section('content')
	<div class="brats-border" v-show="toggle">
		&nbsp;
		<div class="row col s12 col m12">
			<div class="col s12 col m12">
				<center>
					<h2>{{ $trip->route }} <i class="fa fa-bus"></i></h2>
					<h5><u>Seating Instructions:</u></h5>
				</center>
				<div class="card-panel yellow darken-2 white-text z-depth-2">
					<ul class="flow-text">
						<li>
							1. Click the seat(s) you want to reserve until the <b>number of passengers left</b> is equal to 0.
						</li>
						<li>
							2. Click the <b class="green-text"><u>PROCEED <i class="fa fa-check"></i></u></b> button to proceed and purchase your reservations.
						</li>
						<li>&nbsp;
							<p>Tip: If you made a mistake to your seating arrangement. Just click the chosen seat again to remove the person on the chair.</p>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row col s12 col m12">
			<h5 class="flow-text center"><b>Bus Seating Arrangement</b></h5>
			<div class="row col s6 col m6">
				<span class="flow-text">Number of passengers left: <span class="red-text">@{{ passengers_left }}</span> </span>
				<input type="hidden" id="totalPassengers" v-model="passengers_left" value="{{ $trip->totalPassengers }}">
			</div>
			<div class="col s12 col m12">
				<form name="checkOutForm" method="POST" action="{{ url('/book_seats/iterate') }}" accept-charset="utf-8" autocomplete="off"
					@submit.prevent="verifyFields($event)"
				>
					{{ csrf_field() }}	
					{{ method_field('POST') }}
					<input type="hidden" name="bus" value="{{ $trip->bus }}" v-model="SeatProfile.bus">
					<input type="hidden" name="bustype" value="{{ $trip->bustype }}" v-model="SeatProfile.bustype">
					<input type="hidden" name="bustype_id" value="{{ $trip->bustype_id }}" v-model="SeatProfile.bustype_id">
					<input type="hidden" name="dispatch" id="dispatch" value="{{ $trip->dispatch }}" v-model="SeatProfile.dispatch">
					<input type="hidden" name="travel_date" value="{{ $trip->travel_date }}" v-model="SeatProfile.travel_date">
					<input type="hidden" name="travel_time" value="{{ $trip->travel_time }}" v-model="SeatProfile.travel_time">
					<input type="hidden" name="route" value="{{ $trip->route }}" v-model="SeatProfile.route">
					<input type="hidden" name="route_id" value="{{ $trip->route_id }}" v-model="SeatProfile.route_id">
					<input type="hidden" value="{{ $seating->leftColumn }}" v-model="leftColumn">
					<input type="hidden" value="{{ $seating->rightColumn }}" v-model="rightColumn">
					<input type="hidden" value="{{ $seating->totalRows }}" v-model="totalRows">
					<input type="hidden" value="{{ $seating->rowPerColumn }}" v-model="rowPerColumn">

					<!-- DISPLAYING SEATING ARRANGEMENT -->
					<table id="seat_table" class="bordered amber lighten-3">
						<tbody>
							@if ( isset($seats) || $seats->count() )
							<tr>
							@foreach($seats as $seat)
							<!-- {{$ctrRow}} -->
								@if ($ctrRow == $seating->totalRows+1 || $ctrRow == ($seating->totalRows*$seating->leftColumn+2) || $ctrRow == $seating->totalRows*($seating->leftColumn+1)+2)
									@if ($seat->BusSeatStatus_Name == 'Open' || $seat->BusSeatStatus_Name == 'open' || $seat->BusSeatStatus_Name == 'Available' || $seat->BusSeatStatus_Name == 'available')
											<td>
												
												<a id="{{ $seat->BusSeat_Number}}" href="#!" >
													<img src="/images/available_seat.png" alt="available_seat_icon" 
														height="{{ $height }}px" width="{{ $width }}px"
														id="{{ $seat->BusSeat_Number }}" 
														v-on:click.self="reserve($event)" title="Available seat."
													/>
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
												
											</td> 
											@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
											@endif
											<!-- CONDITION 1 -->
									@elseif ($seat->BusSeatStatus_Name == 'Queue' || $seat->BusSeatStatus_Name == 'queue' || $seat->BusSeatStatus_Name == 'On Queue' || $seat->BusSeatStatus_Name == 'on queue')
											<td>
												<a href="#!" id="{{ $seat->BusSeat_Id }}">
													<img src="/images/selected_seat.png" alt="available_seat_icon" height="{{ $height }}px" width="{{ $width }}px" title="Seat is on process of reservation." title="Seat is already in other process of reservation" 
													/>
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
											</td>
											@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
											@endif
									@elseif ($seat->BusSeatStatus_Name == 'Tentative' || $seat->BusSeatStatus_Name == 'tentative')
											<td>
												<a href="#!" id="{{ $seat->BusSeat_Id }}">
													<img src="/images/selected_seat.png" alt="available_seat_icon" height="{{ $height }}px" width="{{ $width }}px" title="Seat is on process of reservation." title="Seat is already in other process of reservation" 
													/>
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
											</td>
											@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
											@endif

									@elseif ($seat->BusSeatStatus_Name == 'Reserved' || $seat->BusSeatStatus_Name == 'reserved' || $seat->BusSeatStatus_Name == 'Reserve' || $seat->BusSeatStatus_Name == 'reserve')
											<td>
												<a href="#!" id="{{ $seat->BusSeat_Id }}" title="Already reserved">
													<img src="/images/reserved_seat.png" alt="available_seat_icon" 
														height="{{ $height }}px" width="{{ $width }}px"
													/>
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
											</td>
											@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
											@endif
											<!-- CONDITION 2 -->
									@elseif ($seat->BusSeatStatus_Name == 'Taken' || $seat->bus_seat_statuses->BusSeat_tatus_Name == 'taken')
											<td>
												<a href="#!" id="{{ $seat->BusSeat_Id }}">
													<img src="/images/taken_seat.png" alt="available_seat_icon" 
														height="{{ $height }}px" width="{{ $width }}px" title="The seat is already purchased."
													/>
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
											</td>
											@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
											@endif
											<!-- CONDITION 3 -->
									@endif
								@elseif ( $ctrRow == ($seating->totalRows*$seating->rightColumn)+1 )
										<tr>
											@for($i = 1; $i<$seating->totalRows; $i++)
												<td></td>
											@endfor
											<?php $ctrRow+= ($seating->totalRows-1); ?>
									@if ($seat->BusSeatStatus_Name == 'Open' || $seat->BusSeatStatus_Name == 'open' || $seat->BusSeatStatus_Name == 'Available' || $seat->BusSeatStatus_Name == 'available')
											<td>
												<a href="#!" id="{{ $seat->BusSeat_Number }}">
													<img src="/images/available_seat.png" alt="available_seat_icon" 
														height="{{ $height }}px" width="{{ $width }}px"
														id="{{ $seat->BusSeat_Number }}" 
														v-on:click.self="reserve($event)" title="Available seat."
													/>														
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
											</td>
											@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
											@endif
											<!-- CONDITION 4 -->

									@elseif ($seat->BusSeatStatus_Name == 'Queue' || $seat->BusSeatStatus_Name == 'queue' || $seat->BusSeatStatus_Name == 'On Queue' || $seat->BusSeatStatus_Name == 'on queue')
											<td>
												<a href="#!" id="{{ $seat->BusSeat_Id }}">
													<img src="/images/selected_seat.png" alt="available_seat_icon" 
													height="{{ $height }}px" width="{{ $width }}px" title="Seat is already in other process of reservation" 
													/>
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
											</td>
											@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
											@endif

									@elseif ($seat->BusSeatStatus_Name == 'Tentative' || $seat->BusSeatStatus_Name == 'tentative')
											<td>
												<a href="#!" id="{{ $seat->BusSeat_Id }}">
													<img src="/images/selected_seat.png" alt="available_seat_icon" height="{{ $height }}px" width="{{ $width }}px" title="Seat is on process of reservation." title="Seat is already in other process of reservation" 
													/>
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
											</td>
											@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
											@endif

									@elseif ($seat->BusSeatStatus_Name == 'Reserved' || $seat->BusSeatStatus_Name == 'reserved' || $seat->BusSeatStatus_Name == 'Reserve' || $seat->BusSeatStatus_Name == 'reserve')
											<td>
												<a href="#!" id="{{ $seat->BusSeat_Id }}" title="Already reserved">
													<img src="/images/reserved_seat.png" alt="available_seat_icon" 
														height="{{ $height }}px" width="{{ $width }}px"
													/>
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
											</td>
											@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
											@endif
											<!-- CONDITION 5 -->
									@elseif ($seat->BusSeatStatus_Name == 'Taken' || $seat->BusSeatStatus_Name == 'taken')
											<td>
												<a href="#!" id="{{ $seat->BusSeat_Id }}">
													<img src="/images/taken_seat.png" alt="available_seat_icon" 
														height="{{ $height }}px" width="{{ $width }}px" title="The seat is already purchased."
													/>
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
											</td>
											@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
											@endif
											<!-- CONDITION 6 -->
									@endif
								@else
									@if ($seat->BusSeatStatus_Name == 'Open' || $seat->BusSeatStatus_Name == 'open' || $seat->BusSeatStatus_Name == 'Available' || $seat->BusSeatStatus_Name == 'available')
										<td>
												<a href="#!" id="{{ $seat->BusSeat_Number }}">
													<img src="/images/available_seat.png" alt="available_seat_icon"
														 height="{{ $height }}px" width="{{ $width }}px"
														 id="{{ $seat->BusSeat_Number }}" 
														 v-on:click.self="reserve($event)"
													/>
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
										</td>
										@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
										@endif
										<!-- CONDITION 7 -->

									@elseif ($seat->BusSeatStatus_Name == 'Queue' || $seat->BusSeatStatus_Name == 'queue' || $seat->BusSeatStatus_Name == 'On Queue' || $seat->BusSeatStatus_Name == 'on queue')
											<td>
												<a href="#!" id="{{ $seat->BusSeat_Id }}">
													<img src="/images/selected_seat.png" alt="available_seat_icon" 
														height="{{ $height }}px" width="{{ $width }}px" title="Seat is already in other process of reservation" 
													/>
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
											</td>
											@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
											@endif
											
									@elseif ($seat->BusSeatStatus_Name == 'Tentative' || $seat->BusSeatStatus_Name == 'tentative')
											<td>
												<a href="#!" id="{{ $seat->BusSeat_Id }}">
													<img src="/images/selected_seat.png" alt="available_seat_icon" height="{{ $height }}px" width="{{ $width }}px" title="Seat is on process of reservation." title="Seat is already in other process of reservation" 
													/>
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
											</td>
											@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
											@endif

									@elseif ( $seat->BusSeatStatus_Name == 'Reserved' || $seat->BusSeatStatus_Name == 'reserved' || $seat->BusSeatStatus_Name == 'Reserve' || $seat->BusSeatStatus_Name == 'reserve')
										<td>
												<a href="#!" id="{{ $seat->BusSeat_Id }}" title="Already reserved">
													<img src="/images/reserved_seat.png" alt="available_seat_icon" 
														height="{{ $height }}px" width="{{ $width }}px"
													/>
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
										</td>
										@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
											@endif
										<!-- CONDITION 8 -->
									@elseif ( $seat->BusSeatStatus_Name == 'Taken' || $seat->BusSeatStatus_Name == 'taken')
										<td>
												<a href="#!" id="{{ $seat->BusSeat_Id }}">
													<img src="/images/taken_seat.png" alt="available_seat_icon" 
														height="{{ $height }}px" width="{{ $width }}px" title="The seat is already purchased."
													/>
												</a>
												<b>{{ $seat->BusSeat_Number }}</b>
										</td>
										<!-- CONDITION 9 -->
										@if (($ctrRow % $seating->rowPerColumn) == 0)
												</tr>
										@endif
									@endif
							@endif	
							<?php $ctrRow++; ?>
						@endforeach
					@endif
							</tr>
						</tbody>
					</table><br>
					<a href="#!">
						<button class="btn waves-effect waves-light btn-primary green right" type="button" @click="submit()">
							<b>PROCEED <i class="fa fa-btn fa-check"></i></b>
						</button>
					</a>
				
			</div>
		</div>
		<div class="row col s12 col m12">
				<div class="row s10 offset-s2 grey lighten-1">
					<table class="centered">
						<thead>
							<tr>
								<th colspan="5">
									<b>LEGEND</b>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><img src = '/images/available_seat.png' alt = 'Available' class="seat-legends"><br>Available</td>
								<td><img src = '/images/taken_seat.png' alt = 'Taken' class="seat-legends"><br>Taken</td>
								<td><img src = '/images/reserved_seat.png' alt = 'Reserved' class="seat-legends"><br>Reserved</td>
								<td><img src = '/images/selected_seat.png' alt = 'Selected' class="seat-legends"><br>On Queue</td>
								<td><img src= '/images/tentative_seat.png' alt= 'Tentative' class="seat-legends"><br>Tentative</td>
							</tr>
						</tbody>
					</table>
				</div> <br>
			</div>
		</div>
			<!-- PAYOR's FORM -->
			<div class="brats-border" v-show="!toggle">
				&nbsp;
				<div class="row col s12 col m12">
					<div class="col s3 col m3">
						<!-- TRIP INFORMATION -->
						<div class="card-panel grey lighten-4 hoverable z-depth-2">
							<table class="striped bordered responsive-table">
								<thead class="yellow accent-5">
									<th class="center" colspan="2">
										<h5>
											<b>Travel Details <i class="fa fa-briefcase"></i></b>
										</h5>
									</th>
								</thead>
								<tbody>
									<tr>
										<td>
											<b>Bus Number</b>
										</td>
										<td>
											{{ $trip->bus }}
										</td>
									</tr>
									<tr>
										<td>
											<b>Bus Type</b>
										</td>
										<td>
											{{ $trip->bustype }}
										</td>
									</tr>
									<tr>
										<td>
											<b>Route</b>
										</td>
										<td>
											{{ $trip->route }}
										</td>
									</tr>
									<tr>
										<td>
											<b>Date of Travel</b>
										</td>
										<td>
											{{ date_format(date_create($trip->travel_date), 'm/d/Y') }}
										</td>
									</tr>
									<tr>
										<td>
											<b>Time of Travel</b>
										</td>
										<td>
											{{ date_format(date_create($trip->travel_time), 'h:i:s a') }}
										</td>
									</tr>
									<tr>
										<td>
											<b>Origin</b>
										</td>
										<td>
											{{ $trip->origin }}
										</td>
									</tr>
									<tr>
										<td>
											<b>Destination</b>
										</td>
										<td>
											{{ $trip->destination }}
										</td>
									</tr>
									<tr>
										<td><b>Number of Passenger(s)</b></td>
										<td>{{ $trip->totalPassengers }} passenger(s)</td>
									</tr>
								</tbody>
							</table>&nbsp;
						</div>
						<div class="card-panel white ligthen-4 hoverable z-depth-2" style="text-align: justify;">
							<center>
								<b class="red-text flow-text">IMPORTANT:</b>
								&nbsp;<br><br>
								<p>
									Reservations made online through this website have online reservation fees and the transaction may be paid via deposits in the bank.
								</p>
								<p>
									An auto-generated PDF file payment voucher will be automatically e-mailed to you as a tangible
									representation of your reservation payment. This will also be your electronic receipt from us.
								</p>
							</center>
						</div>
					</div>
					<div class="col s7 col m7" id="clientForms">
						<div class="card-panel col s12 col m12 grey lighten-4 hoverable" style="width: 130%;">
							<h4 class="center black-text">
								<b>INPUT INFORMATION <i class="fa fa-keyboard-o"></i></b>
							</h4>
							<a href="#" class="flow-text cyan-text left"
							   @click="returnSeatingArrange()"
							>
								Return to Seating Arrangements <i class="fa fa-wheelchair"></i>
							</a> &nbsp;<br>
							<ul class="collapsible popout white-text" data-collapsible="accordion">
								<li>
									<div class="collapsible-header active flow-text yellow accent-4" title="Click to toggle the instructions">
										<b style="padding-left: 20px;">I N S T R U C T I O N S :</b>
										<i class="black-text fa fa-hand-pointer-o"></i>
									</div>
									<div class="collapsible-body yellow white-text darken-2" style="padding: 20px 15px;">
										<span class="flow-text">
											<b>STEP 1:</b>
											 The user must input the basic information needed for the <b>Payor</b> of the reserved tickets.
										</span>&nbsp; <br><br>
										<span class="flow-text">
											<b>STEP 2:</b>
											 Then enter all of the passenger's basic information.<br/>
											Dont worry, the business is all about commitment and these information are all classified.
										</span>&nbsp;<br><br>
										<span>Tip: Click <b>'Instruction'</b> header to hide this steps guide.</span>
									</div>
								</li>
							</ul>
							<div class="row col s12 col m12">
								<input type="hidden" name="totalPassengers" value="{{ $trip->totalPassengers }}">
								<div class="card-panel orange white-text lighten-2 col s12 col m12 z-depth-2 hoverable"
									 v-show="!agreed"
								> &nbsp; <br>
									<h5><b>STEP 1:</b></h5>
									<span class="flow-text">Payor's Information <i class="fa fa-user"></i></span>
									&nbsp; <br>
									<div class="row col s12 col m12">
										<div class="input-field white-text col s4 col m4">
											<input type="text" name="OnlineCustomer_FirstName" required="true" id="payorFirstName" 
													v-model="Payor.payorFirstName" pattern="[A-Za-z ,.'-]{2,150}" class="validate" 
											/>
											<label class="white-text" for="payorFirstName">First Name*</label>
										</div>
										<div class="input-field white-text col s4 col m4">
											<input type="text" name="OnlineCustomer_MiddleName" id="payorMiddleName" 
													v-model="Payor.payorMiddleName" pattern="[A-Za-z ,.'-]{2,150}" class="validate" 
											/>
											<label class="white-text" for="payorMiddleName">Middle Name</label>
										</div>
										<div class="input-field white-text col s4 col m4">
											<input type="text" name="OnlineCustomer_LastName" id="payorLastName" required 
													v-model="Payor.payorLastName" pattern="[A-Za-z ,.'-]{2,150}" class="validate" 
											/>
											<label class="white-text" for="payorLastName">Last Name*</label>
										</div>
									</div>
									<div class="row col s12 col m12">
										<div class="input-field white-text col s4 col m4">
											<input type="email" name="OnlineCustomer_Email" id="payorEmail" required 
													v-model="Payor.payorEmail" class="validate" 
											/>
											<label class="white-text" for="payorEmail">E-mail Address*</label>
										</div>
										<div class="input-field white-text col s5 col m5">
											<input type="text" name="OnlineCustomer_ContactNumber" id="payorContactNumber"
													v-model="Payor.payorContactNumber"
											 />
											<label class="white-text" for="payorContactNumber">Contact Number</label>
										</div>
										<input type="hidden" name="OnlineCustomer_DateOfReservation" value="{{ date('Y-m-d') }}">
									</div>
									<div class="row col s12 col m12">
										<div class="col s8 col m8">
											<span class="white-text">
												By clicking the <b class="white-text">AGREE <i class="fa fa-check"></i></b> button, you agreed to the
												<a href="{{ url('/bus/passengers/terms_and_agreement') }}"
													target="__blank" class="red-text" 
												>
													Terms and Agreements.
												</a>
											</span>
										</div>
										<div class="col s4 col m4">
											<button type="submit"
													class="btn green accent-5 waves-effect waves-light right" 
													@click="agree()" @submit.prevent
											>
												<b>AGREE <i class="fa fa-check"></i></b>
											</button>
										</div>
									</div>
								</div>
								<div class="card-panel green white-text lighten-1 col s12 col m12 z-depth-3 hoverable" v-show="agreed">
									&nbsp;
									<a class="white-text flow-text" href="#" @click="returnPayor()" title="Click to Return to Payor`s Form">
										<i class="fa fa-sign-out"></i>
									</a>
									&nbsp;
									<h5><b>STEP 2: </b></h5>
									<span class="flow-text">Passenger's Information <i class="fa fa-user-plus"></i></span>
										<!-- LOOP THE NUMBER OF PASSENGERS HERE -->
										@for($i = 0; $i < $trip->totalPassengers; $i++)
											<h4 class="flow-text"><b><i class="fa fa-user"></i> Passenger #{{ $i+1 }}</b></h4>
											<div class="row col s12 col m12">
												<div class="input-field col s4 col m4">
													<input class="white-text validate" type="text" id="passengerFirstName" name="passengerFirstName[]" required pattern="[A-Za-z ,.'-]{2,150}"/>
													<label class="white-text" for="passengerFirstName">
														First Name*
													</label>
												</div>
												<div class="input-field col s4 col m4">
													<input class="white-text validate" type="text" id="passengerMiddleName" name="passengerMiddleName[]"
													pattern="[A-Za-z ,.'-]{2,150}"/>
													<label class="white-text" for="passengerMiddleName">
														Middle Name
													</label>
												</div>
												<div class="input-field col s4 col m4">
													<input class="white-text validate" type="text" id="passengerLastName" name="passengerLastName[]" required pattern="[A-Za-z ,.'-]{2,150}"/>
													<label class="white-text" for="passengerLastName">
														Last Name*
													</label>
												</div>
											</div>
											<div class="row col s12 col m12">
												<div class="input-field col s3 col m3">
													<select required name="passengerGender[]" id="passengerGender" class="browser-default green lighten-1 white-text">
														<option selected="" disabled="" value="">Select Gender*</option>
														<option value="Male">Male</option>
														<option value="Female">Female</option>
													</select>
													<!-- <label for="passengerGender" class="white-text">Gender*</label> -->
												</div>
												<div class="input-field col s2 col m2">
													<input required class="white-text validate" type="number" id="passengerAge" name="passengerAge[]" min="3" max="120">
													<label class="white-text" for="passengerAge">Age*</label>
												</div>
												<div class="input-field col s5 col m5">
													<input type="text" name="passengerContactNumber[]" class="white-text" />
													<label class="white-text" for="passengerContactNumber">Contact Number</label>
												</div>
											</div>
											<div class="row col s12 col m12">
												<div class="input-field col s5 col m5">
													<select required id="passengerDestination" name="passengerDestination[]" class="browser-default green lighten-1 white-text">
													<!-- <label class="white-text" for="passengerDestination">Destination</label> -->
														<option selected="" disabled="" value="">Select Destination*</option>
														@foreach($fares as $fare)
															<option value="{{ $fare->Id }}">
																Destination: {{ $fare->Bus_Stop_Place }} / Php {{ $fare->Price }}
															</option>
														@endforeach
													</select>
												</div>
												<div class="input-field col s4 col m4">
													<select required id="passengerSeat" name="passengerSeat[]" class="green lighten-1 white-text browser-default"> 
														<!-- INSERT INTO CLASS `browser-default` to functon -->
														<option class="grey-text" selected="" disabled="" value="">Select Bus Seat*</option>
														<option v-for="choice of choices" value="@{{ choice }}"
																id="@{{ choice }}"
														>
															Seat Number: @{{ choice }}
														</option>
													</select>
													<!-- <label class="white-text" for="passengerSeat">Seat Number:</label> -->
												</div>
											</div>
											<hr>
										@endfor
										&nbsp;
										<div class="row col s12 col m12">
											<div class="left col s4 col m4">
												<button class="btn orange waves-effect waves-light" 
														type="submit" 
												>
													<b>SUBMIT <i class="fa fa-check"></i></b>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@stop

@section('footer')
<script type="text/javascript">
	$(document).ready(function () {
		$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
			$('select').material_select('destroy');
			$('select').material_select();
			// $("select[required]").css({display: "inline", height: 0, padding: 0, width: 0});
		});
	</script>
	<script type="text/javascript" src="/js/compiled/seats_es6.js"></script>
@stop