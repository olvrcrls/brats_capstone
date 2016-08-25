<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\bus_seat as Seat;
use App\bus_seat_status as Status;
use DB;

class SeatController extends Controller
{
    public function ajaxRetrieve(Request $request)
    {
    	$seats = Seat::with('bus_seat_statuses')
    				  ->where('Bus_Id', '=', $request->bus)
    				  ->where('TravelDispatch_Id', '=', $request->dispatch)
    				  ->get();

    	return $seats;
    }

    public function ajaxUpdate_queue(Request $request)
    {
    	
    	$seat = Seat::find($request->seat_id);
        $seat->BusSeatStatus_Id = $this->getSeatQueueId();
        $seat->save(); // updates the seat status of a seat
    }

    public function ajaxUpdate_unqueue(Request $request)
    {
        $seat = Seat::find($request->seat_id);
        $seat->BusSeatStatus_Id = $this->getSeatUnqueueId();
        $seat->save();
        
    }

    private function getSeatUnqueueId()
    {
        $id = Status::select('BusSeatStatus_Id')
                      ->where('BusSeatStatus_Name', '=', 'Open')
                      ->orWhere('BusSeatStatus_Name', '=', 'Available')
                      ->get(); // getting dynamic the BusSeatStatus_Id of the `Queued` seat
        return $id[0]->BusSeatStatus_Id;
    }

    private function getSeatQueueId()
    {
    	$id = Status::select('BusSeatStatus_Id')
    				  ->where('BusSeatStatus_Name', '=', 'On Queue')
    				  ->orWhere('BusSeatStatus_Name', '=', 'Queue')
    				  ->get(); // getting dynamic the BusSeatStatus_Id of the `Queued` seat
    	return $id[0]->BusSeatStatus_Id;
    }
}
