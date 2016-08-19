<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\bus_seat as Seat;

class SeatController extends Controller
{
    public function ajaxRetrieve(Request $request)
    {
    	$seats = Seat::with('bus_seat_statuses')
    				  ->select('BusSeat_Id', 'BusSeat_Number', 'BusSeatStatus_Id')
    				  ->where('Bus_Id', '=', $request->bus)
    				  ->where('TravelDispatch_Id', '=', $request->dispatch)
    				  ->get();

    	return $seats;
    }
}
