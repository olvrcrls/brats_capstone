<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class passenger_ticket extends Model
{
    public $primaryKey = "PassengerTicket_Id";
 	public $table = "passengerticket";
 	public $timestamps = false;

 	protected $fillable = [
 						'PassengerTicket_Id',
 						'Purchase_Id', 'RoutePathWays_Id', 'BusSeat_Id',
 						'TripFareDiscount_Id', 'PassengerTicket_Price', 'Terminal_Id'
 				];

 	public function discounted_passengers()
 	{
 		return $this->hasMany(discounted_passenger_info::class, 'DiscountedPassengerInfo_Id', 'DiscountedPassengerInfo_Id');
 	}

 	public function passengers()
 	{
 		return $this->hasMany(passenger::class, 'Passenger_Id', 'Passenger_Id');
 	}

 	public function purchases()
 	{
 		return $this->belongsTo(purchase::class, 'Purchase_Id', 'Purchase_Id');
 	}

 	public function route_path_ways()
 	{
 		return $this->belongsTo(route_path_ways::class, 'RoutePathWays_Id', 'RoutePathWays_Id');
 	}

 	public function bus_seats()
 	{
 		return $this->belongsTo(bus_seat::class, 'BusSeat_Id', 'BusSeat_Id');
 	}

 	public function trip_fare_discounts()
 	{
 		return $this->belongsTo(trip_fare_discount::class, 'TripFareDiscount_Id', 'TripFareDiscount_Id');
 	}

 	public function terminals()
 	{
 		return $this->belongsTo(terminal::class, 'Terminal_Id', 'Terminal_Id');
 	}
}
