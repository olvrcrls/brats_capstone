<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class travel_schedule extends Model
{
    public $primaryKey = "TravelSchedule_Id";
 	public $table = "travelschedule";
 	public $timestamps = false;

 	public function terminals()
 	{
 		return $this->belongsTo(terminal::class, 'Terminal_Id', 'Terminal_Id');
 	}

 	public function routes()
 	{
 		return $this->belongsTo(route::class, 'Route_Id', 'Route_Id');
 	}

 	public function travel_dispatches()
 	{
 		return $this->hasMany(travel_dispatch::class, 'TravelDispatch_Id', 'TravelDispatch_Id');
 	}
}
