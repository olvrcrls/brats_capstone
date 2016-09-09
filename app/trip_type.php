<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class trip_type extends Model
{
    public $primaryKey = "TripType_Id";
 	public $table = "triptype";
 	public $timestamps = false;

 	public function buses()
 	{
 		return $this->hasMany(bus::class, 'Bus_Id', 'Bus_Id');
 	}

 	public function routes()
 	{
 		return $this->hasMany(route::class, 'Route_Id', 'Route_Id');
 	}

}
