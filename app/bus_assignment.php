<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bus_assignment extends Model
{
    public $primaryKey = "BusAssignment_Id";
 	public $table = "busassignment";
 	public $timestamps = false;

 	public function buses()
 	{
 		return $this->belongsTo(bus::class, 'Bus_Id', 'Bus_Id');
 	}

 	public function drivers()
 	{
 		return $this->belongsTo(driver::class, 'Driver_Id', 'Driver_Id');
 	}

 	public function conductors()
 	{
 		return $this->belongsTo(conductor::class, 'Conductor_Id', 'Conductor_Id');
 	}

 	public function terminals()
 	{
 		return $this->belongsTo(terminal::class, 'Terminal_Id', 'Terminal_Id');
 	}
}
