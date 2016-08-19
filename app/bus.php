<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bus extends Model
{
 	public $primaryKey = "Bus_Id";
 	public $table = "bus";
 	public $timestamps = false;

 	public function travel_dispatches()
 	{
 		return $this->hasMany(travel_dispatch::class, 'TravelDispatch_Id', 'TravelDispatch_Id');
 	}

 	public function bus_seats()
 	{
 		return $this->hasMany(bus_seat::class, 'BusSeat_Id', 'BusSeat_Id');
 	}

 	public function bus_assignments()
 	{
 		return $this->hasMany(bus_assignment::class, 'BusAssignment_Id', 'BusAssignment_Id');
 	}

 	public function bus_types()
 	{
 		return $this->belongsTo(bus_type::class, 'BusType_Id', 'BusType_Id');
 	}

 	public function trip_types()
 	{
 		return $this->belongsTo(trip_type::class, 'TripType_Id', 'TripType_Id');
 	}

 	public function bus_statuses()
 	{
 		return $this->belongsTo(bus_status::class, 'BusStatus_Id', 'BusStatus_Id');
 	}

 	public function terminals()
 	{
 		return $this->belongsTo(terminal::class, 'Terminal_Id', 'Terminal_Id');
 	}
 	
}
