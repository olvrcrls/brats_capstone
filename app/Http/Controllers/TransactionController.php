<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Requests;

use App\bus as Bus;
use App\bus_seat as Seat;
use App\route_path_ways as Path;
use App\online_reservation_fee as OnlineFee;
use DB;
use App\online_customer as OnlineCustomer;
use App\purchase as Purchase;
use App\purchase_type as PurchaseType;
use App\passenger_ticket as Ticket;
use App\passenger as Passenger;
use App\route as Route;
use App\bus_seat_status as SeatStatus;
use App\payment as Payment;
use App\payment_status as PaymentStatus;
use App\payment_history as PaymentHistory;
use App\travel_dispatch as Dispatch;

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

    	 $seats = Seat::join('busseatstatus', 'busseat.BusSeatStatus_Id', '=', 'busseatstatus.BusSeatStatus_Id')
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
    	return view("pages.bus.seats", compact('title','seats','fares', 'trip', 'seating', 'width', 'height', 'ctrRow'));
    }

    public function view(Request $request)
    {
        $this->validate($request, [
            'OnlineCustomer_FirstName' => 'required',
            'OnlineCustomer_LastName' => 'required',
            'OnlineCustomer_Email' => 'required|email',
            'OnlineCustomer_DateOfReservation' => 'required|date',
            'bus' => 'required',
            'bustype' => 'required',
            'dispatch' => 'required',
            'travel_date' => 'required',
            'travel_time' => 'required',
            'route' => 'required',
            'route_id' => 'required',
            ]);
        $totalFarePrice = 0;
        $title = "Review Transaction - Bus Reservation And Ticketing System";
        $passengers = [];
        $fares = DB::select("CALL GetFareMatrixTable($request->bustype_id, $request->route_id)") ;
        $fee = DB::table('onlinereservationfee')
                        ->select('OnlineReservationFee_Amount')
                        ->orderBy('OnlineReservationFee_Id', 'desc')
                        ->take(1)
                        ->get();
        $onlineFee = $fee[0]->OnlineReservationFee_Amount;                
        for($i = 0; $i < $request->totalPassengers; $i++) {
            $objPassenger = new\stdClass;
            $objPassenger->destinationId = $request->passengerDestination[$i];
            $objPassenger->destinationName = Path::select('RoutePathWays_Place')
                                                    ->where('RoutePathWays_Id', '=', $request->passengerDestination[$i])
                                                    ->get();
            $objPassenger->fare = 0;
            for($x = 0; $x < count($fares); $x++) {
                if ($fares[$x]->Id == $objPassenger->destinationId) {
                    $objPassenger->fare = $fares[$x]->Price;
                }
            }
            $objPassenger->seatNumber = $request->passengerSeat[$i];
            $passengers[$i] = $objPassenger;
        }//for
        return view('pages.purchase.iterate', compact('request', 'totalFarePrice', 'passengers', 'title', 'onlineFee'));
    }

    public function store(Request $request)
    {
        $totalFarePrice = 0;
        $onlineFee = $this->getOnlineFee(); //getting the latest online reservation fee.    

        for ($i=0; $i < $request->totalPassengers ; $i++) 
        { 
            $totalFarePrice += $request->fares[$i];
        } //computing total transaction price.

        $totalFarePrice += $onlineFee; 

        // return $totalFarePrice;

        $totalPassengers = $request->totalPassengers; //getting totalpassengers
        $terminal = $this->getTerminal($request->Route_Id); // getting id of terminal
        $purchaseTypeId = $this->getPurchaseType('Online'); // getting id of bus seat status
        $PaymentStatus_Id = $this->getPaymentStatus('Unpaid'); //getting id of payment status


        // inserting of Purchase data
        $purchaseTransaction = Purchase::create([
                'PurchaseType_Id' => $purchaseTypeId,
                'TravelDispatch_Id' => $request->TravelDispatch_Id,
                'Purchase_TotalPrice' => $totalFarePrice,
                'Purchase_Date' => date('Y-m-d h:i:s'),
                'Terminal_Id' => $terminal
            ]);

        $Purchase_Id = $purchaseTransaction->Purchase_Id;
        $PaymentHistory_Amount = $purchaseTransaction->Purchase_TotalPrice;

        // inserting Payment data
        $createPayment = Payment::create([
                    'Purchase_Id' => $Purchase_Id,
                    'PaymentStatus_Id' => $PaymentStatus_Id,
                    'Terminal_Id' => $terminal
            ]);

        $Payment_Id = $createPayment->Payment_Id;

        // inserting of PaymentHistory data
        $createPaymentHistory = PaymentHistory::create([

             'Payment_Id' => $Payment_Id,
             'PaymentHistory_Amount' => $PaymentHistory_Amount,
             'PaymentHistory_Date' => date('Y-m-d h:i:s'),
             'Terminal_Id' => $terminal
         ]);
        // inserting of OnlineCustomer data
        $onlineCustomer = OnlineCustomer::create([
                'OnlineCustomer_FirstName' => $request->OnlineCustomer_FirstName,
                'OnlineCustomer_LastName' => $request->OnlineCustomer_LastName,
                'OnlineCustomer_MiddleName' => $request->OnlineCustomer_MiddleName,
                'OnlineCustomer_Email' => $request->OnlineCustomer_Email,
                'OnlineCustomer_ContactNumber' => $request->OnlineCustomer_ContactNumber,
                'OnlineCustomer_DateOfReservation' => $request->OnlineCustomer_DateOfReservation,
                'Purchase_Id' => $Purchase_Id
            ]);
        $customerId = $onlineCustomer->OnlineCustomer_Id;
        $createTicket = [];
        // inserting of PassengerTicket Data
        for ($i=0; $i < $totalPassengers ; $i++) { 
            $createTicket[$i] = Ticket::create([
                'Purchase_Id' => $Purchase_Id,
                'RoutePathWays_Id' => $request->RoutePathWays_Id[$i],
                'BusSeat_Id' => $this->getBusSeatId($request->BusSeat_Number[$i], $request->TravelDispatch_Id),
                'PassengerTicket_Price' => $request->fares[$i],
                'Terminal_Id' => $terminal,
                'TripFareDiscount_Id' => null
            ]);
        }//for
        //inserting of Passenger(s) data
        for ($i=0; $i < $totalPassengers ; $i++) { 

            $createPassenger = Passenger::create([
                    'Passenger_FirstName' => $request->Passenger_FirstName[$i],
                    'Passenger_LastName' => $request->Passenger_LastName[$i],
                    'Passenger_MiddleName' => $request->Passenger_MiddleName[$i],
                    'Passenger_Age' => $request->Passenger_Age[$i],
                    'Passenger_Gender' => $request->Passenger_Gender[$i],
                    'Passenger_ContactNumber' => $request->Passenger_ContactNumber[$i],
                    'PassengerTicket_Id' => $createTicket[$i]->PassengerTicket_Id,
                    'Terminal_Id' => $terminal
                ]);            
        } //for
        // return 'recorded';
        return redirect('/transaction/voucher/'.$Purchase_Id.'/transaction'.'/'.$customerId.'/viewprint');

    }

    public function manage(Request $request)
    {
        $title = 'Manage Booked Trips - Bus Reservation And Ticketing System';
        return view('pages.purchase.manage', compact('title'));
    }



    public function getBusSeatId($seat, $dispatch)
    {
        $id = Seat::select('BusSeat_Id')
                    ->where('BusSeat_Number', '=', $seat)
                    ->where('TravelDispatch_Id', '=', $dispatch)
                    ->get();

        $status_Id = SeatStatus::select('BusSeatStatus_Id')
                                ->where('BusSeatStatus_Name', '=', 'Reserve')
                                ->orWhere('BusSeatStatus_Name', '=', 'Reserved')
                                ->get();

        $status = $status_Id[0]->BusSeatStatus_Id;
        $seat = $id[0]->BusSeat_Id;
        $affectedSeat = DB::update("update busseat set BusSeatStatus_Id = $status where BusSeat_Id = ?", [$seat]);

        return $seat;
    }

    public function getOnlineFee()
    {
        $fee = OnlineFee::select('OnlineReservationFee_Amount')
                        ->orderBy('OnlineReservationFee_Id', 'desc')
                        ->take(1)
                        ->get();

        return $fee[0]->OnlineReservationFee_Amount;
    }

    public function getPaymentStatus($name)
    {
        $id = PaymentStatus::select('PaymentStatus_Id')
                             ->where('PaymentStatus_Name', '=', $name)
                             ->get();

        return $id[0]->PaymentStatus_Id;
    }

    public function getTerminal($request)
    {
        $Id = Route::select('Terminal_IdStart')
                             ->where('Route_Id', '=', $request)
                             ->get();
        return $Id[0]->Terminal_IdStart;

    }

    public function getPurchaseType($name)
    {
        $id = PurchaseType::select('PurchaseType_Id')
                            ->where('PurchaseType_Name', '=', $name)
                            ->get();
        return $id[0]->PurchaseType_Id;
    }
}
