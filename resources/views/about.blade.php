@extends('layout')
@section('content')
<?php  
use App\utilities_company as Utilities;
        try
        {
            $brand = Utilities::select('UtilitiesCompanyInfo_CompanyName')->orderBy('UtilitiesCompanyInfo_Id', 'desc')->get();
            $brand = $brand[0]->UtilitiesCompanyInfo_CompanyName;
        }
        catch (Exception $e)
        {
            $brand = 'Bus Reservation and Ticketing System';
        }
?>
	<div class="container">
		<h2 class="black-text"><b>About Us <i class="green-text text-darken-3 fa fa-users"></i></b></h2>
		<div class="brats-border">
		<!-- ERASE THE FOLLOWING TEMPLATE FOR THE BUSINESS INFORMATION -->
			<p class="flow-text">
				<b>{{ $brand }}</b> is a Capstone project that is developed and authored by:
			</p>
				<ul class="flow-text">
					<li>
						- <b>Jayson Abilar</b>
					</li>
					<li>
						- <b>Jhunnar Arconada</b>
					</li>
					<li>
						- <b>John Paul Escala</b>
					</li>
					<li>
						- <b>Lennon Flores</b>
					</li>
					<li>
						- <b>Oliver Carlos</b>
					</li>
					<li>
						- <b>Raymon Cabagua</b>
					</li>
				</ul>
				
				<p class="flow-text">
					of class BSIT 4 - 1D (Academic Year: 2016 - 2017) under advisery of Prof. Analyn Ordoñez - Balderas
				</p>
				<br>
				<p class="flow-text" style="text-align: justify;">
					The Bus Reservation And Ticketing System is composed of a standalone window and an online reservation for provincial bus transportations. Provides real-time seating arrangement status and real-time online reservation transactions that can be either paid through deposits in the bank or directly to the cashier of the bus terminal.
				</p>
				<br>
				<br>
				<p class="flow-text">
					Special thanks to the following that helped us :<br>
					- Prof. Aleta Fabregas <br>
					- Prof. AJ Ablir <br>
					- Lastly and most importantly, to our parents with their everlasting support for our education.
				</p>
		</div>
	</div>
@stop