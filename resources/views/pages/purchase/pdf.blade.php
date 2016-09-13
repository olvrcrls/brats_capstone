@extends('layout')
@section('content')
	<div class="brats-border">
		<h2 class=""><b>Printing your Voucher <i class="fa fa-print"></i></b></h2>
		<hr>
		<div class="row s12 m12">
			<div class="col s12 col m12">
				<div class="card white darken-3 z-depth-3 hoverable">
					<div class="card-content black-text">
						<div>
						<center>
							<h3 class='title'>Bus Reservation And Ticketing System Company</h3>
							<h4>123 Saint Bernard Street, Brgy. Tibay, San Juan City Metro Manila Philippines</h4>
							<hr>
							&nbsp;
							<h3><u><b>PAYMENT VOUCHER</b></u></h3>
						</center>
						</div>
						<br>
						<div>
							<table width='100%' >
								<tr>
									<td>
										<b class="flow-text">PAYOR`S COPY</b>
									</td>
								</tr>
								<tr>
									<td>
										<b>Transaction Control Number : {{ $purchase->Purchase_Id }}</b>
									</td>
									<td class='right'>
										<b>Date : ______________________________________________</b>
									</td>
								</tr>
								<tr>
									<td>
										<b>Account Number : 0001230056</b>
									</td>
									<td class="right"> <b>Payment Description :</b>
										<span style='margin-left: 10px;'>( ) Full Payment</span>
										<span>( ) Half Payment</span>
									</td>
									
								</tr>
								<tr>
									<td><b>Customer Number : {{ $customer->OnlineCustomer_Id }}</b></td>
									<td class="right"><b>Mode of Payment : </b>( ) Pay to Cashier   ( ) Pay to Bank</td>
								</tr>
								<tr>
									<td><b>Customer Name : {{ $customer->OnlineCustomer_FirstName.' '.$customer->OnlineCustomer_MiddleName.' '.$customer->OnlineCustomer_LastName }}</b></td>
									<td class="right" style='margin-right: 13%;'><b>Date of Departure : {{ date_format(date_create($dispatch[0]->TravelDispatch_Date), 'm/d/Y') }}</b></td>
								</tr>
								<tr>
									<td><b>Route : {{ $dispatch[0]->Route_Name }}</b></td>
								</tr>
							</table>
							<br><br>
							<table width='100%' class="bordered">
								<thead>
									<th class='thead'>
										Ticket Numbers
									</th>
									<th class='thead'>
										Descriptions
									</th>
									<th class='thead'>
										Prices
									</th>
								</thead>
								<tbody>
									@foreach($tickets as $ticket)
									<tr>
										<td align=''>{{ $ticket->PassengerTicket_Id }}</td>
										<td align=''>{{ $ticket->RoutePathWays_Place }}</td>
										<td align='right'><i class="fa fa-rub"></i> {{ $ticket->PassengerTicket_Price }}</td>
									</tr>
									@endforeach
									<tr>
										<td></td>
										<td>Online Service Fee</td>
										<td align="right"><i class="fa fa-rub"></i> {{ $onlineFee }}</td>
									</tr>
									<tr>
										<td></td>
										<td></td>
										<td class="green-text" align='right'>TOTAL : <b class="flow-text"><i class="fa fa-rub"></i> {{ $purchase->Purchase_TotalPrice }}</b></td>
									</tr>
								</tbody>
							</table><br><br>&nbsp;
								<table>
									<tr>
										<td align="center" class="center">
											
										</td>
										<td align="center" class="center">
											
										</td>
										<td align="left" class="left">
											______________________________________________
										</td>
									</tr>
									<tr>
										<td class='center' align='center'>
											
										</td>
										<td class='center' align='center'>
											
										</td>
										<td class='left' align='left'>
											<b>Authorized Personnel Signature</b>
										</td>
									</tr>
								</table>
								<br><br>&nbsp;
								<b>Remarks: __________________________________________________________________________________________________</b>
								<hr>
								<p>
									<b><i>Instructions </i>:</b>
									<br><br>
									<i>
										This is your copy. Keep this in a safe place. This document valid until <b>{{ date('m/d/Y', strtotime("+$numberOfDays days")) }}</b>
										<br><br>

										If you are half-paid the teller will indicate to your copy of your installment in the remarks field.
										<br>
										Bring this voucher for refunds and cancellations of online reservations to the origin terminal.
										<br><br>
										I expressly agree to the Terms of Use, have read and understand the Terms & Agreement Policy, and confirm that the information that I have provided to the Bus Company website are true and correct to the best of my knowledge.  <br>My submission of this form will constitute my consent to the collection and use of my information and the transfer of information for processing and storage by the Bus Reservation And Ticketing System Company.  <br>Furthermore, I agree and understand that I am legally responsible for the information I entered in the Online Provincial Bus Reservation System and if I violate its Terms of Service my reservation may be revoked or my transaction will be voided.
									</i> 
									<br>
								</p>
								<hr>
								<br><br>

								
								<table width='100%' >
								<tr>
									<td>
										<b class="flow-text">CASHIER`S / TELLER`S COPY</b>
									</td>
									<td class="right">
										<b class="flow-text">ONLINE PAYMENT VOUCHER</b>
									</td>
								</tr>
								<tr>
									<td>
										<b>Transaction Control Number : {{ $purchase->Purchase_Id }}</b>
									</td>
									<td class='right'>
										<b>Date : ______________________________________________</b>
									</td>
								</tr>
								<tr>
									<td>
										<b>Account Number : 0001230056</b>
									</td>
									<td class="right"> <b>Payment Description :</b>
										<span style='margin-left: 10px;'>( ) Full Payment</span>
										<span>( ) Half Payment</span>
									</td>
									
								</tr>
								<tr>
									<td><b>Customer Number : {{ $customer->OnlineCustomer_Id }}</b></td>
									<td class="right"><b>Mode of Payment : </b>( ) Pay to Cashier   ( ) Pay to Bank</td>
								</tr>
								<tr>
									<td><b>Customer Name : {{ $customer->OnlineCustomer_FirstName.' '.$customer->OnlineCustomer_MiddleName.' '.$customer->OnlineCustomer_LastName }}</b></td>
									<td class="right" style='margin-right: 20%;'><b>Route : {{ $dispatch[0]->Route_Name }}</b></td>
								</tr>
							</table>
							<hr>
								<table width='100%' class="bordered">
								<thead>
									<th class='thead'>
										Ticket Numbers
									</th>
									<th class='thead'>
										Descriptions
									</th>
									<th class='thead'>
										Prices
									</th>
								</thead>
								<tbody>
									@foreach($tickets as $ticket)
									<tr>
										<td align=''>{{ $ticket->PassengerTicket_Id }}</td>
										<td align=''>{{ $ticket->RoutePathWays_Place }}</td>
										<td align='right'><i class="fa fa-rub"></i> {{ $ticket->PassengerTicket_Price }}</td>
									</tr>
									@endforeach
									<tr>
										<td></td>
										<td>Online Service Fee</td>
										<td align="right"><i class="fa fa-rub"></i>{{ $onlineFee }}</td>
									</tr>
									<tr>
										<td></td>
										<td></td>
										<td class="green-text" align='right'>TOTAL : <b class="flow-text"><i class="fa fa-rub"></i> {{ $purchase->Purchase_TotalPrice }}</b></td>
									</tr>
								</tbody>
							</table><br><br>&nbsp;
						</div>
					</div>
				</div>
				<br>&nbsp;
				<a href="/voucher/print/{{ $purchase->Purchase_Id }}/{{ $customer->OnlineCustomer_Id }}/VoucherPDF" target="__blank">
					<button class="btn btn-large red waves-effect waves-light">
						<b>Print <i class="fa fa-print"></i></b>
					</button>
				</a>
				<a href="/voucher/save/{{ $purchase->Purchase_Id }}/{{ $customer->OnlineCustomer_Id }}/VoucherPDF" target="__blank">
					<button class="btn btn-large green waves-effec waves-light">
						<b>SAVE <i class="fa fa-download"></i></b>
					</button>
				</a>
			</div>
		</div>
	</div>
@stop
@section('footer')
	<script type="text/javascript">
		$(document).ready(function() {
			alert('An e-mail message has been sent to you as a response that we have received your reservation(s) request.');
		});
	</script>
@stop