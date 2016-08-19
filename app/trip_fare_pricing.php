<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class trip_fare_pricing extends Model
{
    public $primaryKey = "TripFarePricing_Id";
 	public $table = "tripfarepricing";
 	public $timestamps = false;

 	public function bus_types()
 	{
 		return $this->belongsTo(bus_type::class, 'BusType_Id', 'BusType_Id');
 	}
}
