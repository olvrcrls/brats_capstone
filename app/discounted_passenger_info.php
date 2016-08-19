<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class discounted_passenger_info extends Model
{
	public $primaryKey = 'DiscountedPassengerInfo_Id';
	public $table = 'discountedpassengerinfo';
	public $timestamps = false;

    public function passenger_tickets()
    {
    	return $this->belongsTo(passengerticket::class, 'PassengerTicket_Id', 'PassengerTicket_Id');
    }

    public function passengers()
    {
    	return $this->belongsTo(passengers::class, 'Passenger_Id', 'Passenger_Id');
    }
}
