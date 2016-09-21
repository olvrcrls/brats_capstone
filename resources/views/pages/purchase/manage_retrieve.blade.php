@extends('layout')
@section('content')
	<div class="brats-border">
		<h2><b>Manage Booked Trips <i class="fa fa-briefcase"></i></b></h2>
	</div>
	<!-- Displays for voucher request -->
	<div class="brats-border">
		<span class="flow-text">
			<b>Retrieval copy of E-Voucher</b>
		</span><br><br>
		@if (isset($customer->cancelled))
		<div class="row">
			<div class="card red darken-1 white-text z-depth-2 col s8 col m8">
				<span class="flow-text">
				  	<b>
				  		<u>This transaction has already requested a cancellation/refund request.</u> <br><br>
				  		Please contact the customer service at bratscapstone@gmail.com, for more information.
				  	</b>
				 </span>
			</div>
		</div>
		@endif
		@if (isset($customer->expired))
		<!-- Displays if the voucher is already expired -->
		<div class="row">
			<div class="card orange darken-1 white-text z-depth-2 col s8 col m8">
				<h5>
				  	<b>
				  		<u>This E-Voucher is already expired.</u> <br><br> If you haven't been fully paid or paid an installment, your transaction is already forfeited.
				  	</b>
				 </h5>
			</div>
		</div>
		@endif
		<div class="row">
			<div class="card grey lighten-2 flow-text black-text z-depth-2 col s8 col m8">
				<h4>Instructions:</h4>
				<span>
					Good day, <b>Mr./Ms. {{ $customer->name }}!</b>
					<br> Please choose thru the following instructions of ways to retrieve your E-Voucher.
				</span>
				<ul>
					<li>
						> Click the <b class="red-text">PRINT <i class="fa fa-print"></i></b> button in order to directly print your E-Voucher from the web.
					</li>
					<li>
						> Click the <b class="green-text">SAVE <i class="fa fa-download"></i></b> button in order to download your copy of E-Voucher.
					</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<a href="/voucher/print/{{ $customer->Purchase_Id }}/{{ $customer->OnlineCustomer_Id }}/VoucherPDF" target="__blank">
					<button class="btn btn-large red waves-effect waves-light">
						<b>Print <i class="fa fa-print"></i></b>
					</button>
			</a>
			<a href="/voucher/save/{{ $customer->Purchase_Id }}/{{ $customer->OnlineCustomer_Id }}/VoucherPDF" target="__blank">
					<button class="btn btn-large green waves-effec waves-light">
						<b>SAVE <i class="fa fa-download"></i></b>
					</button>
			</a>
		</div>
		<div class="row">
			<div class="col s8 col m8">
				<!-- Display table about information on purchased transaction. -->
			</div>
		</div>
	</div>
@stop