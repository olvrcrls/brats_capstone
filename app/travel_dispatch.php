<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class travel_dispatch extends Model
{
    public $primaryKey = "TravelDispatch_Id";
 	public $table = "traveldispatch";
 	public $timestamps = false;

 	public function terminals()
 	{
 		return $this->belongsTo(terminals::class, 'Terminal_Id', 'Terminal_Id');
 	}

 	public function conductors()
 	{
 		return $this->belongsTo(conductor::class, 'Conductor_Id', 'Conductor_Id');
 	}

 	public function drivers()
 	{
 		return $this->belongsTo(driver::class, 'Driver_Id', 'Driver_Id');
 	}

 	public function buses()
 	{
 		return $this->belongsTo(bus::class, 'Bus_Id', 'Bus_Id');
 	}

 	public function travel_schedules()
 	{
 		return $this->belongsTo(travel_schedule::class, 'TravelSchedule_Id', 'TravelSchedule_Id');
 	}

 	public function bus_seats()
 	{
 		return $this->hasMany(bus_seat::class, 'BusSeat_Id', 'BusSeat_Id');
 	}

 	public function purchases()
 	{
 		return $this->hasMany(purchase::class, 'Purchase_Id', 'Purchase_Id');
 	}
}
