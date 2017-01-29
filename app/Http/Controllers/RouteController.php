<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\route as Route;

class RouteController extends Controller
{
    /**
    * Displays the available route travels.
    *
    * @return \Illuminate\Http\Reponse
    */
    public function index()
    {
    	$title = 'Routes and Schedules - Bus Reservation And Ticketing System';
    	return view('pages.routes.routes', compact('title'));
    }

    /**
    * Fetches the available route trips.
    *
    * @return \App\route $routes
    */
    public function fetch()
    {
        $routes = Route::select('route.Route_Id', 'Route_Name')
                        ->join('travelschedule', 'route.Route_Id', '=', 'travelschedule.Route_Id')
                        ->join('traveldispatch', 'traveldispatch.TravelSchedule_Id', '=', 'travelschedule.TravelSchedule_Id')
                        ->join('bus', 'bus.Bus_Id', '=', 'traveldispatch.Bus_Id')
                        ->join('triptype', 'bus.TripType_Id', '=', 'triptype.TripType_Id')
                        ->where('TripType_Name', '=', 'Provincial')
                        ->where('route.Record_Status', '=', 'Active')
                        ->orderBy('Route_Name', 'asc')
                        ->distinct()
                        ->get();
        return $routes;
    }
}
