<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class route extends Model
{
    public $primaryKey = "Route_Id";
 	public $table = "route";
 	public $timestamps = false;

 	public function travel_schedules()
 	{
 		return $this->hasMany(travel_schedule::class, 'TravelSchedule_Id', 'TravelSchedule_Id');
 	}

 	public function terminals()
 	{
 		return $this->belongsTo(terminal::class, 'Terminal_Id', 'Terminal_Id');
 	}

 	public function trip_types()
 	{
 		return $this->belongsTo(trip_type::class, 'TripType_Id', 'TripType_Id');
 	}

 	public function route_path_ways()
 	{
 		return $this->hasMany(route_path_ways::class, 'RoutePathWays_Id', 'RoutePathWays_Id');
 	}
}
