<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bus_type extends Model
{
    public $primaryKey = "BusType_Id";
 	public $table = "bustype";
 	public $timestamps = false;

 	public function trip_fare_pricings()
 	{
 		return $this->hasMany(trip_fare_pricing::class, 'TripFarePricing_Id', 'TripFarePricing_Id');
 	}

 	public function buses()
 	{
 		return $this->hasMany(bus::class, 'Bus_Id', 'Bus_Id');
 	}
}
