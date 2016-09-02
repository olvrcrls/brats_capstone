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
                      ->join('busseatstatus', 'busseat.BusSeatStatus_Id', '=', 'busseatstatus.BusSeatStatus_Id')
    				  ->where('Bus_Id', '=', $request->bus)
    				  ->where('TravelDispatch_Id', '=', $request->dispatch)
    				  ->get();

    	return $seats;
    }

    public function ajaxCheck(Request $request)
    {
        $status = Seat::select('BusSeatStatus_Name')
                        ->join('busseatstatus', 'busseat.BusSeatStatus_Id', '=', 'busseatstatus.BusSeatStatus_Id')
                        ->where('BusSeat_Number', '=', $request->seat_number)
                        ->where('Bus_Id', '=', $request->bus)
                        ->where('TravelDispatch_Id', '=', $request->dispatch)
                        ->get();

        return $status[0]->BusSeatStatus_Name;
    }

    public function ajaxUpdate_tentative(Request $request)
    {
    	
    	$seat = Seat::where('BusSeat_Number', '=', $request->seat_number)
                      ->where('Bus_Id', '=', $request->bus)
                      ->where('TravelDispatch_Id', '=', $request->dispatch)
                      ->get();
        $seat[0]->BusSeatStatus_Id = $this->getSeatTentativeId(); // can also use model's function Seat::getBusSeatStatusId('statusName')
        $seat[0]->save(); // updates the seat status of a seat
    }

    public function ajaxUpdate_queue(Request $request)
    {
        foreach ($request->seats as $seat) {
            $seat = Seat::where('BusSeat_Number', '=', $seat)
                      ->where('Bus_Id', '=', $request->bus)
                      ->where('TravelDispatch_Id', '=', $request->dispatch)
                      ->get();
            $seat[0]->BusSeatStatus_Id = $this->getSeatQueueId(); // can also use model's function Seat::getBusSeatStatusId('statusName')
            $seat[0]->save(); // updates the seat status of a seat
        }      
        // $seat = Seat::where('BusSeat_Number', '=', $request->seat_number)
        //               ->where('Bus_Id', '=', $request->bus)
        //               ->where('TravelDispatch_Id', '=', $request->dispatch)
        //               ->get();
        // $seat[0]->BusSeatStatus_Id = $this->getSeatQueueId(); // can also use model's function Seat::getBusSeatStatusId('statusName')
        // $seat[0]->save(); // updates the seat status of a seat
    }

    public function ajaxUpdate_unqueue(Request $request)
    {
        $seat = Seat::where('BusSeat_Number', '=', $request->seat_number)
                      ->where('Bus_Id', '=', $request->bus)
                      ->where('TravelDispatch_Id', '=', $request->dispatch)
                      ->get();
        $seat[0]->BusSeatStatus_Id = $this->getSeatUnqueueId(); // can also use model's function Seat::getBusSeatStatusId('statusName')
        $seat[0]->save();
        
    }

    public function ajaxUpdate_setAvailable_all(Request $request)
    {
        foreach ($request->seats as $seat) {
            $seat = Seat::where('BusSeat_Number', '=', $seat)
                      ->where('Bus_Id', '=', $request->bus)
                      ->where('TravelDispatch_Id', '=', $request->dispatch)
                      ->get();
            $seat[0]->BusSeatStatus_Id = $this->getSeatUnqueueId(); // can also use model's function Seat::getBusSeatStatusId('statusName')
            $seat[0]->save(); // updates the seat status of a seat
        }     
    }

    public function ajaxUpdate_unqueue_all(Request $request)
    {
        foreach ($request->seats as $seat) {
            $seat = Seat::where('BusSeat_Number', '=', $seat)
                      ->where('Bus_Id', '=', $request->bus)
                      ->where('TravelDispatch_Id', '=', $request->dispatch)
                      ->get();
            $seat[0]->BusSeatStatus_Id = $this->getSeatTentativeId(); // can also use model's function Seat::getBusSeatStatusId('statusName')
            $seat[0]->save(); // updates the seat status of a seat
        }    
    }

    private function getSeatUnqueueId()
    {
        $id = Status::select('BusSeatStatus_Id')
                      ->where('BusSeatStatus_Name', '=', 'Open')
                      ->orWhere('BusSeatStatus_Name', '=', 'Available')
                      ->get(); // getting dynamic the BusSeatStatus_Id of the `Queued` seat
        return $id[0]->BusSeatStatus_Id;
    }

    private function getSeatTentativeId()
    {
        $id = Status::select('BusSeatStatus_Id')
                      ->where('BusSeatStatus_Name', '=', 'Tentative')
                      ->get();
        return $id[0]->BusSeatStatus_Id; // getting dynamic BusSeatStatus_Id of the 'Tentative' seat
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
