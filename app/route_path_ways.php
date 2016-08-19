<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class route_path_ways extends Model
{
    public $primaryKey = "RoutePathWays_Id";
 	public $table = "routepathways";
 	public $timestamps = false;

 	public function passenger_tickets()
 	{
 		return $this->hasMany(passenger_ticket::class, 'PassengerTicket_Id', 'PassengerTicket_Id');
 	}

 	public function routes()
 	{
 		return $this->belongsTo(route::class, 'Route_Id', 'Route_Id');
 	}

 	public function terminals()
 	{
 		return $this->belongsTo(terminal::class, 'Terminal_Id', 'Terminal_Id');
 	}
}
