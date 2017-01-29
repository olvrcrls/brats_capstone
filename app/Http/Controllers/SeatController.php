<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\bus_seat as Seat;
use App\bus_seat_status as Status;
use DB;

class SeatController extends Controller
{
  /**
  * Retrieves the whole bus seats of a bus
  * from a specific dispatch schedule.
  *
  * @param \Illuminate\Http\Request $request
  * @return JSON $seats
  */
    public function ajaxRetrieve(Request $request)
    {
    	$seats = Seat::with('bus_seat_statuses')
                      ->join('busseatstatus', 'busseat.BusSeatStatus_Id', '=', 'busseatstatus.BusSeatStatus_Id')
    				  ->where('Bus_Id', '=', $request->bus)
    				  ->where('TravelDispatch_Id', '=', $request->dispatch)
    				  ->get();

    	return $seats;
    }

    /**
    * Retrieves the bus seat's status name
    * from a specific dispatch schedule.
    *
    * @param \Illuminate\Http\Request $request
    * @return JSON $status
    */
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

  /**
  * Sets the bus seat's status as a selected seat
  * from a specific dispatch schedule.
  *
  * @param \Illuminate\Http\Request $request
  * @return JSON $seatNumber
  */
    public function ajaxUpdate_tentative(Request $request)
    {
    	
    	$seat = Seat::where('BusSeat_Number', '=', $request->seat_number)
                      ->where('Bus_Id', '=', $request->bus)
                      ->where('TravelDispatch_Id', '=', $request->dispatch)
                      ->get();
        $seat[0]->BusSeatStatus_Id = $this->getSeatQueueId(); // can also use model's function Seat::getBusSeatStatusId('statusName')
        $seat[0]->save(); // updates the seat status of a seat
        return $this->getSeatQueueId();
    }

    /**
    * Sets the bus seat into a queue as the
    * client is already filling up the forms for
    * these seats from a specific dispatch schedule.
    *
    * @param \Illuminate\Http\Request $request
    * @return void
    */
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
    }

  /**
  * Resets the bus seat's statuses as the
  * connection fails or if the client refreshes
  * the window or tend to break contract.
  *
  * @param \Illuminate\Http\Request $request
  * @return void
  */
    public function ajaxUpdate_unqueue(Request $request)
    {
        $seat = Seat::where('BusSeat_Number', '=', $request->seat_number)
                      ->where('Bus_Id', '=', $request->bus)
                      ->where('TravelDispatch_Id', '=', $request->dispatch)
                      ->get();
        $seat[0]->BusSeatStatus_Id = $this->getSeatUnqueueId(); // can also use model's function Seat::getBusSeatStatusId('statusName')
        $seat[0]->save();
    }

  /**
  * Resets all the bus seats' statuses as the
  * connection fails or if the client refreshes
  * the window or tend to break contract.
  *
  * @param \Illuminate\Http\Request $request
  * @return void
  */
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

  /**
  * Cancels the queueing of the bus seats
  * connection fails or if the client refreshes
  * the window or tend to break contract.
  *
  * @param \Illuminate\Http\Request $request
  * @return void
  */
    public function ajaxUpdate_unqueue_all(Request $request)
    {
        foreach ($request->seats as $seat) {
            $seat = Seat::where('BusSeat_Number', '=', $seat)
                      ->where('Bus_Id', '=', $request->bus)
                      ->where('TravelDispatch_Id', '=', $request->dispatch)
                      ->get();
            $seat[0]->BusSeatStatus_Id = $this->getSeatQueueId(); // can also use model's function Seat::getBusSeatStatusId('statusName')
            $seat[0]->save(); // updates the seat status of a seat
        }    
    }

    /**
    * Retrieving the seat status id of a
    * specific bus seat
    * @return int $id
    */
    private function getSeatUnqueueId()
    {
        $id = Status::select('BusSeatStatus_Id')
                      ->where('BusSeatStatus_Name', '=', 'Open')
                      ->orWhere('BusSeatStatus_Name', '=', 'Available')
                      ->get(); // getting dynamic the BusSeatStatus_Id of the `Queued` seat
        return $id[0]->BusSeatStatus_Id;
    }

    /**
    * Retrieving the seat status id of a
    * specific bus seat
    * @return int $id
    */
    private function getSeatQueueId()
    {
    	$id = Status::select('BusSeatStatus_Id')
    				  ->where('BusSeatStatus_Name', '=', 'On Queue')
    				  ->orWhere('BusSeatStatus_Name', '=', 'Queue')
    				  ->get(); // getting dynamic the BusSeatStatus_Id of the `Queued` seat
    	return $id[0]->BusSeatStatus_Id;
    }
}
