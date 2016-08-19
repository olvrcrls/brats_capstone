<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class conductor extends Model
{
    public $primaryKey = "Conductor_Id";
 	public $table = "conductor";
 	public $timestamps = false;

 	public function travel_dispatches()
 	{
 		return $this->hasMany(travel_dispatch::class, 'TravelDispatch_Id', 'TravelDispatch_Id');
 	}

 	public function bus_assignments()
 	{
 		return $this->hasMany(bus_assignment::class, 'BusAssignment_Id', 'BusAssignment_Id');
 	}
 	
 	public function terminals()
 	{
 		return $this->hasMany(terminal::class, 'Terminal_Id', 'Terminal_Id');
 	}
}
