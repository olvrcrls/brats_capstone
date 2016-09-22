<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\travel_dispatch as Dispatch;
use App\travel_schedule as Schedule;
use App\route as Path;
use App\trip_type as TripType;
use App\bus_seat as Seat;
use App\days_span_to_reserve as ReservationDay;

class ScheduleController extends Controller
{
    public function show(Request $request)
    {
    	// from welcome page url('/')

    	$this->validate($request, [ 'origin' => 'required', 'destination' => 'required', 'travel_date' => 'required|date']);
    	$route = Path::select('Route_Id', 'Route_Name')
    				 ->where('Terminal_IdStart', '=', $request->origin)
    				 ->where('Terminal_IdDestination', '=', $request->destination)
    				 ->where('Record_Status', '=', 'Active')
    				 ->get();
    	//getting the path that is requested
    	if( ! $route->count())
    	{
    			return redirect()->action('HomeController@fail');
    	} //if there is no route
    	
   		$dispatches = Dispatch::select('TravelDispatch_Id', 'traveldispatch.Bus_Id', 'TravelDispatch_Date', 'BusType_Name', 'BusStatus_Name', 'TravelSchedule_Time', 'bustype.BusType_Id')
   							  ->join('bus', 'traveldispatch.Bus_Id', '=', 'bus.Bus_Id')
   							  ->join('bustype', 'bus.BusType_Id', '=', 'bustype.BusType_Id')
   							  ->join('busstatus', 'bus.BusStatus_Id', '=', 'busstatus.BusStatus_Id')
   							  ->join('triptype', 'bus.TripType_Id', '=', 'triptype.TripType_Id')
   							  ->join('travelschedule', 'traveldispatch.TravelSchedule_Id', '=', 'travelschedule.TravelSchedule_Id')
   							  ->where('TravelDispatch_Date', '=', $request->travel_date)
   							  ->where('travelschedule.Route_Id', '=', $route[0]->Route_Id)
   							  ->where('triptype.TripType_Name', '=', 'Provincial')
   							  ->where('BusStatus_Name', '=', 'On Queue')
   							  ->orWhere('BusStatus_Name', '=', 'Available')
   							  ->orderBy('TravelDispatch_Date', 'asc')
   							  ->get();

		if(!$dispatches->count())
    	{
    			return redirect()->action('HomeController@fail');
    	} //if there is no route   							  
   		
    	// return $dispatches->count();
   		$dispatch_schedules = [];

   		 for( $i = 0; $i < $dispatches->count(); $i++)
   		 {

   		 	$dispatch_schedule = new\stdClass;
   		 	$dispatch_schedule->TravelDispatch_Id = $dispatches[$i]->TravelDispatch_Id;
   		 	$dispatch_schedule->route_id = $route[0]->Route_Id;
   		 	$dispatch_schedule->travel_date = $dispatches[$i]->TravelDispatch_Date;
   		 	$dispatch_schedule->bus = $dispatches[$i]->Bus_Id;
   		 	$dispatch_schedule->bustype_id = $dispatches[$i]->BusType_Id;
   		 	$dispatch_schedule->bustype = $dispatches[$i]->BusType_Name;
   		 	$dispatch_schedule->status = $dispatches[$i]->BusStatus_Name;
   		 	$dispatch_schedule->route = $route[0]->Route_Name;
   		 	$dispatch_schedule->time = $dispatches[$i]->TravelSchedule_Time;
   		 	$seats = Seat::join('busseatstatus', 'busseat.BusSeatStatus_Id', '=', 'busseatstatus.BusSeatStatus_Id')
   		 					->where('TravelDispatch_Id', '=', $dispatches[$i]->TravelDispatch_Id)
   		 					->where('BusSeatStatus_Name', '=', 'Open')
   		 					->orWhere('BusSeatStatus_Name', '=', 'Available')
   		 					->count();
   		 	$dispatch_schedule->seats = $seats; //totalSeats available
   		 	$dispatch_schedules[$i] = $dispatch_schedule;
   		 }//for
   		 $title = 'Available Bus Schedules - Bus Reservation And Ticketing System';
   		 return view('pages.schedules.schedules', compact('dispatch_schedules','title'));

    }



    public function route_show(Path $route)
    {
        // return $route;
        $now = date("Y-m-d");
        $interval = date('Y-m-d', strtotime($now. ' + 3 days')); //since the only allowed days before reserving a ticket is a minimum of 3 days
        //All Schedules are picked from a specific route
          $dispatches = Dispatch::select('TravelDispatch_Id', 'traveldispatch.Bus_Id', 'TravelDispatch_Date', 'BusType_Name', 'BusStatus_Name', 'TravelSchedule_Time', 'bustype.BusType_Id')
                  ->join('bus', 'traveldispatch.Bus_Id', '=', 'bus.Bus_Id')
                  ->join('bustype', 'bus.BusType_Id', '=', 'bustype.BusType_Id')
                  ->join('busstatus', 'bus.BusStatus_Id', '=', 'busstatus.BusStatus_Id')
                  ->join('triptype', 'bus.TripType_Id', '=', 'triptype.TripType_Id')
                  ->join('travelschedule', 'traveldispatch.TravelSchedule_Id', '=', 'travelschedule.TravelSchedule_Id')
                  ->where('TravelDispatch_Date', '>=', $interval )
                  ->where('travelschedule.Route_Id', '=', $route->Route_Id)
                  ->where('triptype.TripType_Name', '=', 'Provincial')
                  ->where('BusStatus_Name', '=', 'On Queue')
                  ->orWhere('BusStatus_Name', '=', 'Available')
                  ->orderBy('TravelDispatch_Date', 'asc')
                  ->get();

    if( ! $dispatches->count())
      {
          return redirect()->action('HomeController@fail');
      } //if there is no route                  
      
      // return $dispatches->count();
      $dispatch_schedules = [];

       for( $i = 0; $i < $dispatches->count(); $i++)
       {

        $dispatch_schedule = new\stdClass;
        $dispatch_schedule->TravelDispatch_Id = $dispatches[$i]->TravelDispatch_Id;
        $dispatch_schedule->route_id = $route->Route_Id;
        $dispatch_schedule->travel_date = $dispatches[$i]->TravelDispatch_Date;
        $dispatch_schedule->bus = $dispatches[$i]->Bus_Id;
        $dispatch_schedule->bustype_id = $dispatches[$i]->BusType_Id;
        $dispatch_schedule->bustype = $dispatches[$i]->BusType_Name;
        $dispatch_schedule->status = $dispatches[$i]->BusStatus_Name;
        $dispatch_schedule->route = $route->Route_Name;
        $dispatch_schedule->time = $dispatches[$i]->TravelSchedule_Time;
        $seats = Seat::join('busseatstatus', 'busseat.BusSeatStatus_Id', '=', 'busseatstatus.BusSeatStatus_Id')
                ->where('TravelDispatch_Id', '=', $dispatches[$i]->TravelDispatch_Id)
                ->where('BusSeatStatus_Name', '=', 'Open')
                ->orWhere('BusSeatStatus_Name', '=', 'Available')
                ->count();
        $dispatch_schedule->seats = $seats; //totalSeats available
        $dispatch_schedules[$i] = $dispatch_schedule;
       }//for
       $title = 'Available Bus Schedules - Bus Reservation And Ticketing System';
       return view('pages.schedules.schedules', compact('dispatch_schedules','title'));
    }

    public function fetchDays()
    {
        try {
         return $days = ReservationDay::select('DaysSpanToReserve_Days')->orderBy('DaysSpanToReserve_Id', 'desc')->take(1)->get(); 
        } catch (Exception $e) {
          return 1;
        }
    }
}
