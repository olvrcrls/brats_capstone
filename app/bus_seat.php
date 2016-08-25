<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bus_seat extends Model
{
    public $primaryKey = "BusSeat_Id";
 	public $table = "busseat";
 	public $timestamps = false;
 	protected $fillable = ['BusSeatStatus_Id'];

 	public function travel_dispatches()
 	{
 		return $this->belongsTo(travel_dispatch::class, 'TravelDispatch_Id', 'TravelDispatch_Id');
 	}

 	public function buses()
 	{
 		return $this->belongsTo(bus::class, 'Bus_Id', 'Bus_Id');
 	}

 	public function bus_seat_statuses()
 	{
 		return $this->belongsTo(bus_seat_status::class, 'BusSeatStatus_Id', 'BusSeatStatus_Id');
 	}

 	public function terminals()
 	{
 		return $this->belongsTo(terminal::class, 'Terminal_Id', 'Terminal_Id');
 	}
}
