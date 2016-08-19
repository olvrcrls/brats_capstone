<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class terminal extends Model
{
    public $primaryKey = "Terminal_Id";
 	public $table = "terminal";
 	public $timestamps = false;

 	public function travel_schedule()
 	{
 		return $this->hasMany(travel_schedule::class, 'TravelSchedule_Id', 'TravelSchedule_Id');
 	}

 	public function travel_dispatches()
 	{
 		return $this->hasMany(travel_dispatch::class, 'TravelDispatch_Id', 'TravelDispatch_Id');
 	}

 	public function toddler_passengers()
 	{
 		return $this->hasMany(toddler_passenger::class, 'ToddlerPassenger_Id', 'ToddlerPassenger_Id');
 	}

 	public function route_path_ways()
 	{
 		return $this->hasMany(route_path_ways::class, 'RoutePathWays_Id', 'RoutePathWays_Id');
 	}

 	public function routes()
 	{
 		return $this->hasMany(route::class, 'Route_Id', 'Route_Id');
 	}

 	public function payment_histories()
 	{
 		return $this->hasMany(payment_history::class, 'PaymentHistory_Id', 'PaymentHistory_Id');
 	}

 	public function purchases()
 	{
 		return $this->hasMany(purchase::class, 'Purchase_Id', 'Purchase_Id');
 	}

 	public function payments()
 	{
 		return $this->hasMany(payment::class, 'Payment_Id','Payment_Id');
 	}

 	public function passenger_tickets()
 	{
 		return $this->hasMany(passenger_ticket::class, 'PassengerTicket_Id', 'PassengerTicket_Id');
 	}

 	public function passengers()
 	{
 		return $this->hasMany(passenger::class, 'Passenger_Id', 'Passenger_Id');
 	}

 	public function buses()
 	{
 		return $this->hasMany(bus::class, 'Bus_Id', 'Bus_Id');
 	}

 	public function bus_assignments()
 	{
 		return $this->hasMany(bus_assignment::class, 'BusAssignment_Id', 'BusAssignment_Id');
 	}

 	public function bus_seats()
 	{
 		return $this->hasMany(bus_seat::class, 'BusSeat_Id', 'BusSeat_Id');
 	}
 	
 	public function conductors()
 	{
 		return $this->hasMany(conductor::class, 'Conductor_Id', 'Conductor_Id');
 	}

 	public function drivers()
 	{
 		return $this->hasMany(driver::class, 'Driver_Id', 'Driver_Id');
 	}

}
