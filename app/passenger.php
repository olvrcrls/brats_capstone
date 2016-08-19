<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class passenger extends Model
{
    public $primaryKey = "Passenger_Id";
 	public $table = "passenger";
 	public $timestamps = false;

 	protected $fillable = [
 								'Passenger_FirstName', 'Passenger_MiddleName', 'Passenger_LastName',
 								'Passenger_Age', 'Passenger_Gender', 'Passenger_ContactNumber',
 								'PassengerTicket_Id', 'Terminal_Id'
 							];

 	public function passenger_tickets()
 	{
 		return $this->belongsTo(passenger_ticket::class, 'PassengerTicket_Id', 'PassengerTicket_Id');
 	}

 	public function terminal()
 	{
 		return $this->belongsTo(terminal::class, 'Terminal_Id', 'Terminal_Id');
 	}

 	public function discounted_passengers()
 	{
 		return $this->hasMany(discounted_passenger_info::class, 'DiscountedPassengerInfo_Id', 'DiscountedPassengerInfo_Id');
 	}
}
