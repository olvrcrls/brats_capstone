<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bus_seat_status extends Model
{
    public $primaryKey = "BusSeatStatus_Id";
 	public $table = "busseatstatus";
 	public $timestamps = false;

 	public function bus_seats()
 	{
 		return $this->hasMany(bus_seat::class, 'BusSeat_Id', 'BusSeat_Id');
 	}

 	public function passenger_tickets()
 	{
 		return $this->hasMany(passenger_ticket::class, 'PassengerTicket_Id', 'PassengerTicket_Id');
 	}
}
