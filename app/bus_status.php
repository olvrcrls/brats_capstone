<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bus_status extends Model
{
    public $primaryKey = "BusStatus_Id";
 	public $table = "busstatus";
 	public $timestamps = false;

 	public function buses()
 	{
 		return $this->hasMany(bus::class, 'Bus_Id', 'Bus_Id');
 	}
}
