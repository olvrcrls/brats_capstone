<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class driver extends Model
{
    public $primaryKey = "Driver_Id";
 	public $table = "driver";
 	public $timestamps = false;

 	public function bus_assignments()
 	{
 		return $this->hasMany(bus_assignment::class, 'BusAssignment_Id', 'BusAssignment_Id');
 	}

 	public function terminals()
 	{
 		return $this->belongsTo(terminal::class, 'Terminal_Id', 'Terminal_Id');
 	}

 	public function travel_dipatches()
 	{
 		return $this->hasMany(travel_dispatch::class, 'TravelDispatch_Id', 'TravelDispatch_Id');
 	}

}
