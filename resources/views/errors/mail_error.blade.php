@extends('layout')
@section('content')
<div class="container-fluid">
		<div class="container">
			<h1 class="red-text">Connection Problem. <i class="fa fa-info"></i></h1>&nbsp;
			<div>
				<h3>
					There is a problem in your connection. <br><br>The following might be:
					<ul>
						<li>
							<p class="flow-text">
								- Checking the network cables, modem, and router.
							</p>
						</li>
						<li>
							<p class="flow-text">
								- Your internet connection might be too slow or may have no internet connection anymore.
							</p>
						</li>
						<li>
							<p class="flow-text">
								- Reconnecting to Wi-Fi
							</p>
						</li>
					</ul>
				</h3>
				<h3>	
					Possible solutions:
					<ul>
						<li>
							<p class="flow-text">
								- Referesh the window again.
							</p>
						</li>
						<li>
							<p class="flow-text">
								- Or go to "Manage Booked Trips" on the navigation bar and request your E-Voucher. <br>
								  Enter the following: <br>
								  - <b>Transaction Number Reference</b> is: <b class="green-text">{{ $transactionNumber }}</b><br>
								  - And the <b>online customer's last name</b>.
							</p>
						</li>
					</ul>
				</h3>
			</div>
		</div>
</div><br/><br/><br/>
@stop