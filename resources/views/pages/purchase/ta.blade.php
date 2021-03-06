@extends('layout')
@section('content')
	&nbsp;<br>
	<div class="brats-border">
		<h3 class="center">Terms and Agreements <i class="fa fa-lock"></i></h3>
		<div class="container justify">
			<ul>
				<li>
					<p class="flow-text">
						The site and its services offered therein, is owned and operated by the Company Owner of the website and all rights  are
						reserved by the Owners. <br><br>The Company Owner would like to inform the end-users that this website is owned and operated by a
						registered company and is locally based in the Philippines which only offers local bus transportation only.
					</p>
				</li>
				<li>
					<p class="flow-text">
						The Company Owner informs any user of the reservation site that the contents and trademarks are exclusively owned property which includes the logo, visual design, content, etc.
					</p>
				</li>
				<li>
						<p class="flow-text">
							Any unethical actions and violation to the cyber law towards this website will be apprehended.
						</p>
				</li>
				&nbsp;
				<li>
					<h4 class="center">Ticket Reservations <i class="fa fa-tag"></i></h4>
					<p class="flow-text">
							- Reservations are made for <b>{{ $days }} days in advance</b>. In order for the company to have sufficient time and working days to accomodate those who are
							using the online  services for reserving their tickets.
					</p>	
				</li>
				<li>
					<p class="flow-text">
						- <!-- Reserved tickets should be paid <b>before</b> the departure of the said trip. Failure to comply, the reservation will be voided and
					      the reserved seats will be given oppurtunity to the other commuters inside the terminal. -->
					      Reserved seats should be at least half-paid in <b>{{ $voidDays }} days or less</b> after the reservation date. Failure to comply, the reservation of the seats will be voided and these will be opened to other online customers for reservation.
					</p>
				</li>
				<li>
					<p class="flow-text">
						- Reserved Tickets that are already fully paid / half paid can only be refunded within <b>{{ $totalDays }} days from the date of the ticket(s) reservation</b>.
						<br><br>
						Percentage of refunded money as every day lapse. <br>
						<ul>
							@foreach ($percentages as $percentage)
							<li>
								<b class="red-text"> > {{ $percentage->ReserveCancellationPercentage_PercentageReturn }}%</b>
								 refund money of the total price when refunded 
								 @if ($percentage->ReserveCancellationPercentage_NumberOfDays <= 1)
								 	less than 24 hours from the payment date.
								 @else
								 	{{ $percentage->ReserveCancellationPercentage_NumberOfDays }} after the payment date.
								 @endif
							</li>
							@endforeach
						</ul>
					</p>
				</li>
				<li>
					<p class="flow-text">
						- Ticket fees are either half paid to the bank or fully paid to the bank. If half paid, the remaining balance should be
						  paid to the origin bus terminal of the ticket reservations.
					</p>
				</li>
				<li>
					<p class="flow-text">
						- Reservations that are still unpaid, must be half-paid or fullypaid <b>{{ $totalDays }} or less after the reservation date</b> of the reservation else the reservation will be voided.
					</p>
				</li>
				<li>
					<p class="flow-text">
						- Reserved tickets are transferable but <b>only the Payor</b> has the only rights to refund for the paid reserved tickets.
						   He/She must bring his/her <b><i>copy of the E-Voucher with the authorized signature</i></b>, together with his/her receipt if paid to the bus terminal's cashier directly.
					</p>
				</li>
			</ul>
		</div>
	</div>
@endsection