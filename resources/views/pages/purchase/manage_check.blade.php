@extends('layout')
@section('content')
	<div class="brats-border">
		<!-- Content here -->
		<h2><b>Manage Booked Trips <i class="fa fa-briefcase"></i></b></h2>
		<div class="row">
			<div class="col s3 col m3">
				<div class="card-panel white z-depth-3 hoverable">
					<!-- Trip details --><br>
					<table class="striped bordered responsive-table">
						<thead class="yellow accent-5">
							<tr>
								<th colspan="2">
									<center>
										<h5><b>Travel Details <i class="fa fa-briefcase"></i></b></h5>
									</center>
								</th>
							</tr>	
						</thead>
						<tbody>
							<tr>
								<td>
									<b>Route:</b>
								</td>
								<td>
									{{ $infos[0]->Route_Name }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Bus Number:</b>
								</td>
								<td>
									{{ $infos[0]->Bus_Id }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Bus Plate Number:</b>
								</td>
								<td>
									{{ $infos[0]->Bus_PlateNumber }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Bus Type:</b>
								</td>
								<td>
									{{ $infos[0]->BusType_Name }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Bus Status:</b>
								</td>
								<td>
									{{ $infos[0]->BusStatus_Name }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Departure Date:</b>
								</td>
								<td>
									{{ date_format(date_create($infos[0]->TravelDispatch_Date), 'm/d/Y') }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Departure Time:</b>
								</td>
								<td>
									{{ date_format(date_create($infos[0]->TravelSchedule_Time), 'h:i:s A') }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Number of Passengers</b>
								</td>
								<td>
									{{ $infos->count() }}
								</td>
							</tr>
						</tbody>
					</table>
					&nbsp;
				</div>
				<div>
					&nbsp;
					<br>
					<span class="flow-text">
						<b><u>Transaction's E-Voucher <i class="fa fa-file"></i></u></b>
					</span><br><br>
					<a href="/voucher/print/{{ $infos[0]->Purchase_Id }}/{{ $infos[0]->OnlineCustomer_Id }}/VoucherPDF" target="__blank">
					<button class="btn btn-large red waves-effect waves-light">
						<b>Print <i class="fa fa-print"></i></b>
					</button>
					</a>
					<a href="/voucher/save/{{ $infos[0]->Purchase_Id }}/{{ $infos[0]->OnlineCustomer_Id }}/VoucherPDF" target="__blank">
							<button class="btn btn-large green waves-effec waves-light">
								<b>SAVE <i class="fa fa-download"></i></b>
							</button>
					</a>
				</div>
			</div>
			<div class="card-panel green lighten-2 hoverable col s8 col m8 offset-s1 offset-m1">
				<!-- Customer & Passenger details -->
				<div class="white-text">
					<h4>Customer Information <i class="fa fa-user"></i></h4>
					<br>
					<table class="bordered responsive-table">
						<tbody class="flow-text">
							<tr>
								<td>
									Customer's Name: {{ $infos[0]->OnlineCustomer_FirstName.' '.$infos[0]->OnlineCustomer_MiddleName.' '.$infos[0]->OnlineCustomer_LastName.' '}}
								</td>
							</tr>
							<tr>
								<td>
									Date of Reservation: {{ date_format(date_create($infos[0]->Purchase_Date), 'm/d/Y') }}
								</td>
							</tr>
							<tr>
								<td>
									Time of Reservation: {{ date_format(date_create($infos[0]->Purchase_Date), 'h:i:s A') }}
								</td>
							</tr>
							<tr>
								<td>
									Total Cost: Php {{ $infos[0]->Purchase_TotalPrice }}
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<br>
				<div class="white-text flow-text">
					<h4>Passengers Information <i class="fa fa-users"></i></h4>
					<table class="bordered responsive-table">
						<tbody>
							<?php 
									$i = 1;
							?>
							@foreach ($infos as $info)
								<tr>
									<td>
										<h5><b>Passenger #{{ $i }}</b></h5>
									</td>
								</tr>
								<tr>
									<td>
										<b>Name:</b> <i>{{ $info->Passenger_FirstName.' '.$info->Passenger_MiddleName.' '.$info->Passenger_LastName }}</i>
									</td>
								</tr>
								<tr>
									<td>
										<span><b>Ticket Number:</b> <i>{{ $info->PassengerTicket_Id }}</i></span>
									</td>
								</tr>
								<tr>
									<td>
										<span><b>Ticket Price:</b> <i>{{ $info->PassengerTicket_Price }}</i></span>
									</td>
								</tr>
								<tr>
									<td>
										<b>Destination:</b> <i>{{ $info->RoutePathWays_Place }}</i>
									</td>
								</tr>
								<?php $i++; ?>
							@endforeach
						</tbody>
					</table><br>
				</div>
			</div>
		</div>
	</div>
@stop