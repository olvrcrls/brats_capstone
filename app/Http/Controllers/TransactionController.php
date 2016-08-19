<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\bus as Bus;
use App\bus_seat as Seat;
use DB;
class TransactionController extends Controller
{
    public function input(Request $request)
    {
    	$this->validate($request, [
    		'travel_date' => 'required|date',
    		'bus' => 'required',
    		'dispatch' => 'required',
    		'route' => 'required',
    		'route_id' => 'required',
    		'bustype' => 'required',
    		'bustype_id' => 'required',
    		'travel_time' => 'required',
    		'passengerNumber' => 'required'
    		]); //validation
 
    	$fares = DB::select("CALL GetFareMatrixTable($request->bustype_id, $request->route_id)");
    	//getting the fares for each destinations of the Route

    	 $seats = Seat::with('travel_dispatches.buses', 'bus_seat_statuses')
        				->join('bus', 'busseat.Bus_Id', '=', 'bus.Bus_Id')
        				->join('bustype', 'bustype.BusType_Id', '=', 'bus.BusType_Id')
        				->where('TravelDispatch_Id', $request->dispatch)
                        ->where('busseat.Bus_Id', $request->bus)
                        ->orderBy('BusSeat_Id')
                        ->get();
    	//getting the specific seat arrangement of the bus and dispatch travel

         if( !$seats->count())
         {
         	return back();
         }

        $trip = new\stdClass;
    	$trip->travel_date = $request->travel_date;
    	$trip->bus = $request->bus;
    	$trip->dispatch = $request->dispatch;
    	$trip->route = $request->route;
    	$trip->route_id = $request->route_id;
    	$trip->bustype = $request->bustype;
    	$trip->bustype_id = $request->bustype_id;
    	$trip->travel_time = $request->travel_time;
    	$trip->totalPassengers = $request->passengerNumber;
    	$trip->origin = strstr($request->route, '-', true);
    	$trip->destination = substr(strstr($request->route, '-'), 1);
    	//inherting the trip details.

    	$seating = new\stdClass;
    	$seating->totalRows = $seats[0]->BusType_RowsPerColumnCount;
    	$seating->rowPerColumn = $seats[0]->BusType_RowsPerColumnCount;
    	$seating->leftColumn = $seats[0]->BusType_LowerBoxColumnCount; //lowerbox
    	$seating->rightColumn = $seats[0]->BusType_UpperBoxColumnCount; //upperbox

    	$width = floor(650/$seating->totalRows);//computed width for each seat.
        $height = floor((220 - ($seating->rightColumn + $seating->leftColumn)) / ($seating->rightColumn + $seating->leftColumn + 1)); 
        //computed height for each seat.
        $ctrRow = 1; //initial counter for each index of seats.
    	
    	$title = "Travel Transaction ". "($request->route)" ."- Bus Reservation And Ticketing System";
    	return view("pages.bus.seats", compact('title', 'fares', 'trip', 'seating', 'width', 'height', 'ctrRow'));
    }

}
