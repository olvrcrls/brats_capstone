<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class trip_fare_discount extends Model
{
    public $primaryKey = "TripFareDiscount_Id";
 	public $table = "tripfarediscount";
 	public $timestamps = false;

 	public function passenger_tickets()
 	{
 		return $this->hasMany(passenger_ticket::class, 'PassengerTicket_Id', 'PassengerTicket_Id');
 	}
}
