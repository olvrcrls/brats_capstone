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
use App\reserve_cancellation_percentage as Percentage;
use App\reserve_cancellation as Cancellation;

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
        $fee = DB::table('reservationfee')
                        ->select('ReservationFee_Amount')
                        ->orderBy('ReservationFee_Id', 'desc')
                        ->take(1)
                        ->get();
        $onlineFee = $fee[0]->ReservationFee_Amount;                
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
        $percentages = Percentage::select('ReserveCancellationPercentage_NumberOfDays', 'ReserveCancellationPercentage_PercentageReturn')
                              ->get();
        $totalDays = Percentage::count();
        return view('pages.purchase.iterate', compact('request', 'totalFarePrice', 'passengers', 'title', 'onlineFee', 'percentages', 'totalDays'));
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

    public function retrieve(Request $request)
    {
        $title = 'Manage Booked Trips - Bus Reservation And Ticketing System';
        
        // checks first if the request is valid.
        if ($request->purchaseRequest == 'voucher' || $request->purchaseRequest == 'check' || $request->purchaseRequest == 'cancel')
        {
            if ($request->purchaseRequest  == 'voucher') // checks if the request service is E-Voucher retrieval
            {
                $customerInformation = OnlineCustomer::select('OnlineCustomer_Id', 'TravelDispatch_Id', 'onlinecustomer.Purchase_Id','Purchase_Date',
                                                            'OnlineCustomer_FirstName', 'OnlineCustomer_LastName')
                                        ->join('purchase', 'purchase.Purchase_Id', '=', 'onlinecustomer.Purchase_Id')
                                        ->where('OnlineCustomer_LastName', '=', $request->purchaseLastName)
                                        ->where('onlinecustomer.Purchase_Id', '=', $request->purchaseReference)
                                        ->orderBy('OnlineCustomer_Id', 'desc')
                                        ->get(); // checks the online customer with the correct last name & transaction reference number

                if (!$customerInformation->count())
                {
                    $status = "There is no such booked transaction found.";
                    return view('pages.purchase.manage', compact('title', 'status'));
                } // checks if there is such transaction is found.

                $today = date('m/d/Y');
                $purchaseDate = date_format(date_create($customerInformation[0]->Purchase_Date), 'm/d/Y'); // converts into Month/Day/Year of the purchaseDate
                $expireDate = date('m/d/Y', strtotime($purchaseDate. '+ 3 days')); // computes the expiration date

                $customer = new\stdClass;
                $customer->OnlineCustomer_Id = $customerInformation[0]->OnlineCustomer_Id;
                $customer->Purchase_Id = $customerInformation[0]->Purchase_Id;
                $customer->name = $customerInformation[0]->OnlineCustomer_FirstName.' '.$customerInformation[0]->OnlineCustomer_LastName;
                if ($today > $expireDate)
                {
                    $customer->expired = true;
                } // checks if the voucher is already expired.
                return view('pages.purchase.manage_retrieve', compact('title', 'customer'));
            }

            else if ($request->purchaseRequest == 'check')
            {
                $infos = OnlineCustomer::select('OnlineCustomer_Id', 'onlinecustomer.Purchase_Id'
                                                    ,'Passenger_FirstName', 'Passenger_MiddleName', 'Passenger_LastName',
                                                                 'routepathways.RoutePathWays_Place', 'paymentstatus.PaymentStatus_Name',
                                                                 'route.Route_Name', 'traveldispatch.TravelDispatch_Date', 'travelschedule.TravelSchedule_Time', 'passengerticket.PassengerTicket_Id',
                                                                 'OnlineCustomer_FirstName', 'OnlineCustomer_MiddleName', 'OnlineCustomer_LastName',
                                                                 'bus.Bus_Id', 'BusType_Name', 'bus.Bus_PlateNumber', 'busstatus.BusStatus_Name',
                                                                 'purchase.Purchase_Date', 'purchase.Purchase_TotalPrice', 'PassengerTicket_Price'
                                                                 )
                                                        ->join('purchase', 'onlinecustomer.Purchase_Id', '=', 'purchase.Purchase_Id')
                                                        ->join('passengerticket', 'passengerticket.Purchase_Id', '=', 'purchase.Purchase_Id')
                                                        ->join('passenger', 'passengerticket.PassengerTicket_Id', '=', 'passenger.PassengerTicket_Id')
                                                        ->join('routepathways', 'routepathways.RoutePathWays_Id', '=', 'passengerticket.RoutePathWays_Id')
                                                        ->join('payment', 'payment.Purchase_Id', '=', 'onlinecustomer.Purchase_Id')
                                                        ->join('paymentstatus', 'payment.PaymentStatus_Id', '=', 'paymentstatus.PaymentStatus_Id')
                                                        ->join('traveldispatch', 'purchase.TravelDispatch_Id', '=', 'traveldispatch.TravelDispatch_Id')
                                                        ->join('travelschedule', 'traveldispatch.TravelSchedule_Id', '=', 'travelschedule.TravelSchedule_Id')
                                                        ->join('route', 'route.Route_Id', '=', 'travelschedule.Route_Id')
                                                        ->join('bus', 'bus.Bus_Id', '=', 'traveldispatch.Bus_Id')
                                                        ->join('bustype', 'bus.BusType_Id', '=', 'bustype.BusType_Id')
                                                        ->join('busstatus', 'bus.BusStatus_Id', '=', 'busstatus.BusStatus_Id')
                                                        ->where('purchase.PurchaseType_Id', '=', PurchaseType::getId('Online'))
                                                        ->where('OnlineCustomer_LastName', '=', $request->purchaseLastName)
                                                        ->where('onlinecustomer.Purchase_Id', '=', $request->purchaseReference)
                                                        ->get();
                if (!$infos->count())
                {
                    $status = "There is no such booked transaction found.";
                    return view('pages.purchase.manage', compact('title', 'status'));
                }// checks if there is such transaction is found.
                else
                    return view('pages.purchase.manage_check', compact('title', 'infos'));
            }
            else if ($request->purchaseRequest == 'cancel')
            { 
                 $purchase = OnlineCustomer::select('OnlineCustomer_Id', 'onlinecustomer.Purchase_Id', 'purchase.Purchase_Date', 'PaymentStatus_Name', 'paymenthistory.PaymentHistory_Date', 'route.Route_Name', 'bus.Bus_Id', 'bus.Bus_PlateNumber', 'busstatus.BusStatus_Name', 'bustype.BusType_Name', 'purchase.Purchase_TotalPrice', 'onlinecustomer.Purchase_Id')
                                            ->join('purchase', 'onlinecustomer.Purchase_Id', '=', 'purchase.Purchase_Id')
                                            ->join('payment', 'payment.Purchase_Id', '=', 'purchase.Purchase_Id')
                                            ->join('paymentstatus', 'payment.PaymentStatus_Id', '=', 'paymentstatus.PaymentStatus_Id')
                                            ->join('paymenthistory', 'paymenthistory.Payment_Id', '=', 'payment.Payment_Id')
                                            ->join('traveldispatch', 'traveldispatch.TravelDispatch_Id', '=', 'purchase.TravelDispatch_Id')
                                            ->join('travelschedule', 'travelschedule.TravelSchedule_Id', '=', 'traveldispatch.TravelSchedule_Id')
                                            ->join('route', 'route.Route_Id', '=', 'travelschedule.Route_Id')
                                            ->join('bus', 'bus.Bus_Id', '=', 'traveldispatch.Bus_Id')
                                            ->join('bustype', 'bus.BusType_Id', '=', 'bustype.BusType_Id')
                                            ->join('busstatus', 'busstatus.BusStatus_Id', '=', 'bus.BusStatus_Id')
                                            ->where('purchase.PurchaseType_Id', '=', PurchaseType::getId('Online'))
                                            ->where('onlinecustomer.Purchase_Id', '=', $request->purchaseReference)
                                            ->where('OnlineCustomer_LastName', '=', $request->purchaseLastName)
                                            ->orderBy('paymenthistory.Payment_Id', 'desc')
                                            ->take(1)
                                            ->get();
                if (!$purchase->count())
                {
                    $status = "There is no such booked transaction found.";
                    return view('pages.purchase.manage', compact('title', 'status'));
                }
                // check first if cancellation can be done.
                $isCancelled = Cancellation::where('reservecancellation.Purchase_Id', '=', $purchase[0]->Purchase_Id);

                if($isCancelled->count() > 0)
                {
                    $status = "This transaction has already requested a cancellation or refund.";
                    return view('pages.purchase.manage', compact('title', 'status'));
                }

                else
                {
                    $today = date('Y-m-d');
                    $today = new\DateTime($today); //parsing to Date Time
                    $total_num_of_days = Percentage::count(); // total number of days before the transaction is forfeited

                    if ($purchase[0]->PaymentStatus_Name == 'Unpaid' || $purchase[0]->PaymentStatus_Name == 'unpaid')
                    {
                        $parsePurchaseDate = date_format(date_create($purchase[0]->Purchase_Date), 'Y-m-d');
                        $parsePurchaseDate = new\DateTime($parsePurchaseDate);
                        $difference = $parsePurchaseDate->diff($today); // finding the difference between the date today and the date reserved
                        if ($difference->days > $total_num_of_days)
                        {
                            $status = "Sorry but your transaction cannot be cancelled anymore.";
                            return view('pages.purchase.manage', compact('title', 'status'));
                        }
                        else
                        {
                            // gets the remaining balance
                            if ($purchase[0]->PaymentStatus_Name == 'Fully Paid' || $purchase[0]->PaymentStatus_Name == 'fully paid')
                                $costLeft = 0.00;
                            else if ($purchase[0]->PaymentStatus_Name == 'Partially Paid' || $purchase[0]->PaymentStatus_Name == 'partially paid' ||
                                    $purchase[0]->PaymentStatus_Name == 'Half Paid' || $purchase[0]->PaymentStatus == 'half paid')
                                $costLeft = $purchase[0]->Purchase_TotalPrice / 2;
                            else 
                                $costLeft = $purchase[0]->Purchase_TotalPrice;

                            return view('pages.purchase.manage_cancel', compact('title', 'purchase', 'costLeft'));
                        }
                    } // if unpaid

                    else if ($purchase[0]->PaymentStatus_Name == 'Fully Paid' || $purchase[0]->PaymentStatus_Name == 'fully paid' ||
                             $purchase[0]->PaymentStatus_Name == 'Partially Paid' || $purchase[0]->PaymentStatus_Name == 'partially paid' ||
                             $purchase[0]->PaymentStatus_Name == 'Half Paid' || $purchase[0]->PaymentStatus == 'half paid')
                    {
                        $parsePaymentDate = date_format(date_create($purchase[0]->PaymentHistory_Date), 'Y-m-d');
                        $parsePaymentDate = new\DateTime($parsePaymentDate);
                        $difference = $parsePaymentDate->diff($today);
                        if ($difference->days > $total_num_of_days)
                        {
                            $status = "Sorry but your transaction cannot be cancelled anymore.";
                            return view('pages.purchase.manage', compact('title', 'status'));
                        }
                        else
                        {
                            // gets the remaining balance
                            if ($purchase[0]->PaymentStatus_Name == 'Fully Paid' || $purchase[0]->PaymentStatus_Name == 'fully paid')
                                $costLeft = 0.00;
                            else if ($purchase[0]->PaymentStatus_Name == 'Partially Paid' || $purchase[0]->PaymentStatus_Name == 'partially paid' ||
                                    $purchase[0]->PaymentStatus_Name == 'Half Paid' || $purchase[0]->PaymentStatus == 'half paid')
                                $costLeft = $purchase[0]->Purchase_TotalPrice / 2;
                            else 
                                $costLeft = $purchase[0]->Purchase_TotalPrice;
                           
                            return view('pages.purchase.manage_cancel', compact('title', 'purchase', 'costLeft'));
                        }
                    } // if fully paid or partially paid
                    
                }
            }
        } // checks if the user has valid request

        else 
        { 
            $status = "Invalid request of the user.";
            return view('pages.purchase.manage', compact('title', 'status')); 
        } // redirects back if invalid request

    }

    public function cancel(Request $request)
    {
        $this->validate($request, [
                'cancelReason' => 'required',
                'purchaseDate' => 'required',
                'purchaseId' => 'required'
            ]);

        $datetimeOfCancellation = date('Y-m-d h:i:s');
        $dateOfCancellation = date('Y-m-d');
        $parseDateOfCancellation = new\DateTime($dateOfCancellation);
        $parseDateOfPurchase = new\DateTime($request->purchaseDate);
        $difference = $parseDateOfPurchase->diff($parseDateOfCancellation); // gets the difference in days between the date of reservation and date of cancellation.

        $reason = $request->cancelReason;
        if (isset($request->cancelReasonText))
        {
            $reason = $request->cancelReasonText;
        } // if the chosen option is 'Other' then the statement of reason is required.

        if ($difference->days < 1)
            $elapseDays = 1;
        else
            $elapseDays = $difference->days + 1;
        try
        {
            // return $request->all();
            $rate = Percentage::select('ReserveCancellationPercentage_Id','ReserveCancellationPercentage_PercentageReturn')
                                ->where('ReserveCancellationPercentage_NumberOfDays', '=', $elapseDays)
                                ->get();
            $purchasePrice = Purchase::select('Purchase_TotalPrice', 'PaymentStatus_Name', 'PaymentHistory_Amount')
                                        ->join('payment', 'payment.Purchase_Id', '=', 'purchase.Purchase_Id')
                                        ->join('paymentstatus', 'paymentstatus.PaymentStatus_Id', '=', 'payment.PaymentStatus_Id')
                                        ->join('paymenthistory', 'paymenthistory.Payment_Id', '=', 'payment.Payment_Id')
                                        ->where('purchase.Purchase_Id', '=', $request->purchaseId)
                                        ->get();
            if ($purchasePrice[0]->PaymentStatus_Name == 'Unpaid' || $purchasePrice[0]->PaymentStatus_Name == 'unpaid')
                $price = 0.00;
            else if ($purchasePrice[0]->PaymentStatus == 'Partially Paid' || $purchasePrice[0]->PaymentStatus == 'partially paid' ||
                     $purchasePrice[0]->PaymentStatus_Name == 'Half Paid' || $purchasePrice[0]->PaymentStatus == 'half paid')
                $price = $purchasePrice[0]->Purchase_TotalPrice / 2;
            else
                $price = $purchasePrice[0]->Purchase_TotalPrice;

            $rate_id = $rate[0]->ReserveCancellationPercentage_Id;
            $rate = $rate[0]->ReserveCancellationPercentage_PercentageReturn / 100;
            $totalRefundMoney = ($price * $rate);

            Cancellation::insert([
                'ReserveCancellation_Reason' => $reason,
                'ReserveCancellation_AmountReturn' => $totalRefundMoney,
                'Purchase_Id' => $request->purchaseId,
                'ReserveCancellationPercentage_Id' => $rate_id,
                'ReserveCancellation_Status' => 'Pending',
                'ReserveCancellation_DateOfCancelation' => $datetimeOfCancellation
            ]);

            $title = "Cancellation Request - Bus Reservation And Ticketing System";
            $successCancellation = "Your request for cancellation has been sent!";
            return view('pages.purchase.manage', compact('title', 'successCancellation'));
        }
        catch (Exception $e)
        {
            return back();
        }

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
        $fee = OnlineFee::select('ReservationFee_Amount')
                        ->orderBy('ReservationFee_Id', 'desc')
                        ->take(1)
                        ->get();

        return $fee[0]->ReservationFee_Amount;
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
