<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Requests;
use App; //dompdf
use App\online_customer as Customer;
use App\purchase as Purchase;
use App\travel_dispatch as Dispatch;
use App\passenger_ticket as Ticket;
use App\online_reservation_fee as OnlineFee;

class EmailController extends Controller
{
    public function index(Request $request, Purchase $purchase, Customer $customer)
    {
    	$title = "View Print - Bus Reservation And Ticketing System";
    	$dispatch = Dispatch::select('Route_Name', 'TravelDispatch_Date', 'TravelSchedule_Time')
                              ->where('TravelDispatch_Id', '=', $purchase->TravelDispatch_Id)
                              ->join('travelschedule', 'traveldispatch.TravelSchedule_Id', '=', 'travelschedule.TravelSchedule_Id')
                              ->join('route', 'travelschedule.Route_Id', '=', 'route.Route_Id')
                              ->get();

        $tickets = Ticket::select('PassengerTicket_Price', 'RoutePathWays_Place', 'PassengerTicket_Id')
                                   ->where('Purchase_Id', '=',$purchase->Purchase_Id)
                                   ->join('routepathways', 'routepathways.RoutePathWays_Id', '=', 'passengerticket.RoutePathWays_Id')
                                   ->get();

       $onlineFee = $this->getOnlineFee(); 

    	$customer_name = $customer->OnlineCustomer_FirstName.' '.$customer->OnlineCustomer_MiddleName.' '.$customer->OnlineCustomer_LastName;
    	$departure_date = date_format(date_create($dispatch[0]->TravelDispatch_Date), 'm/d/Y');
    	$route = $dispatch[0]->Route_Name;
    	$valid = date_format(date_sub(date_create($dispatch[0]->TravelDispatch_Date), date_interval_create_from_date_string("1 day")), 'm/d/Y');
    	/*
    	 * CREATING PDF FILE
    	 */
    		$html = "<!DOCTYPE html>
                    <html>
                    <head>
                        <title>
                            E-Voucher #$purchase->Purchase_Id Bus Reservation And Ticketing System Company
                        </title>
                        <link rel='stylesheet' href='./css/app.css'/>
                    </head>
                    <body>
                        <div class='brats-border'>
                            <table width='100%'>
                                <tr align='center'>
                                     <td>
                                         <h2>
                                             <b>
                                             <img src='./logo.png' width='100px' height='100px' align='center'>
                                                 Bus Reservation And Ticketing System Company
                                             </b>
                                         </h2>
                                     </td>
                                </tr>
                                <tr align='center'>
                                     <td>
                                        123 Saint Bernard Street, Brgy. Tibay, San Juan City Metro Manila Philippines
                                        <hr>
                                     </td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr align='center'>
                                    <td>
                                      <h1>
                                        <b><u>
                                            PAYMENT VOUCHER
                                        </u></b>
                                      </h1>
                                    </td>
                                </tr>
                            </table>
                            <table width='100%'>
                                <tr>
                                    <td>
                                        <b>PAYOR'S COPY<b><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Payment Date:</b> ________________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Transaction Number :</b> $purchase->Purchase_Id
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Account Number :</b> 000239010
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Payment Description :</b> 
                                    </td>
                                    <td>
                                        <b>Mode of Payment : </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        [ ] Full Payment 
                                        [ ] Half Payment
                                    </td>
                                    <td>
                                        [ ] Cashier
                                        [ ] Bank
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br><br>
                                        <b>Customer Name:</b> $customer_name
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Route:</b> $route 
                                    </td>
                                    <td>
                                        <b>Date of Departure:</b> $departure_date
                                    </td>
                                </tr>
                            </table>
                            <br><br>
                            <table width='100%' border='1'>
                                <thead>
                                    <tr>
                                        <th rowspan='2' class='voucher_th'>
                                            Ticket Number(s)
                                        </th>
                                            <th rowspan='2'>
                                                Descriptions
                                            </th>
                                            <th rowspan='2'>
                                                Prices
                                            </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    </tr>
                            "; //$html

                            foreach($tickets as $ticket)
                            {
                            	$html .= "<tr>
                                        <td align='center'>".
                                            $ticket->PassengerTicket_Id
                                        ."</td>
                                        <td align='center'>".
                                            $ticket->RoutePathWays_Place
                                        ."</td>
                                        <td align='right'> Php ".
                                           $ticket->PassengerTicket_Price
                                        ."</td>
                                    </tr>";
                            }// foreach payor
                       $html .=  "<tr>
                                        <td></td>
                                        <td align='center'>Online Service Fee</td>
                                        <td align='right'>Php $onlineFee</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td align='right'><b>---------------------------</b></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td align='right'>Total:</td>
                                        <td align='right'><b>Php $purchase->Purchase_TotalPrice</b></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br><br>
                            <table width='100%'>
                                <tr>
                                    <td>
                                        _____________________________
                                    </td>
                                    <td>
                                        _____________________________
                                    </td>
                                    <td>
                                        _____________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td align='center'>
                                        <b>Received By</b>
                                    </td>
                                    <td align='center'>
                                        <b>Paid By</b>
                                    </td>
                                    <td align='center'>
                                        <b>Authorized Personnel Signature</b>
                                    </td>
                                </tr>
                            </table>
                            <span>
                                <h4><b>Remarks</b></h4>
                            <span>
                            ____________________________________________________
                            <br><br>
                            <hr>
                            <p>
                                <b><i>Instructions:</i></b>
                                <br><br>
                                    <i>
                                        This is your copy. Keep this in a safe place. This document is valid until <b>$valid</b>
                                        <br><br>

                                        If you are half-paid the teller will indicate to your copy of your installment in the remarks field.
                                        <br>
                                        Bring this voucher for refunds and cancellations of online reservations to the origin terminal.
                                        <br><br>
                                        I expressly agree to the Terms of Use, have read and understand the Terms & Agreement Policy, and confirm that the information that I have provided to the Bus Company website are true and correct to the best of my knowledge.  <br>My submission of this form will constitute my consent to the collection and use of my information and the transfer of information for processing and storage by the Bus Reservation And Ticketing System Company.  <br>Furthermore, I agree and understand that I am legally responsible for the information I entered in the Online Provincial Bus Reservation System and if I violate its Terms of Service my reservation may be revoked or my transaction will be voided.
                                    </i> 
                                    <br>
                            <p>
                            <hr>
                        </div>";
                        // CASHIER / TELLERS
                        $html .= "<div>
                             <table width='100%'>
                                <tr align='center'>
                                     <td>
                                         <h2>
                                             <b>
                                             <img src='./logo.png' width='100px' height='100px' align='center'>
                                                 Bus Reservation And Ticketing System Company
                                             </b>
                                         </h2>
                                     </td>
                                </tr>
                                <tr align='center'>
                                     <td>
                                        123 Saint Bernard Street, Brgy. Tibay, San Juan City Metro Manila Philippines
                                        <hr>
                                     </td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr align='center'>
                                    <td>
                                      <h1>
                                        <b><u>
                                            PAYMENT VOUCHER
                                        </u></b>
                                      </h1>
                                    </td>
                                </tr>
                            </table>
                            <table width='100%'>
                                <tr>
                                    <td>
                                        CASHIER'S / TELLER'S COPY
                                    </td>
                                    <td>
                                        ONLINE PAYMENT VOUCHER
                                    </td>
                                </tr>
                                <br><br>
                                <tr>
                                    <td>
                                        <b>Payment Date:</b> _____________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Transaction Number:</b> $purchase->Purchase_Id
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Account Number:</b> 000239010
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Payment Description :</b> 
                                    </td>
                                    <td>
                                        <b>Mode of Payment : </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        [ ] Full Payment 
                                        [ ] Half Payment
                                    </td>
                                    <td>
                                        [ ] Cashier
                                        [ ] Bank
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br><br>
                                        <b>Customer Name:</b> $customer_name
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Route:</b> $route
                                    </td>
                                    <td>
                                        <b>Date of Departure:</b> $departure_date
                                    </td>
                                </tr>
                            </table>
                            <br><br>
                            <table width='100%' border='1'>
                                <thead>
                                    <tr>
                                        <th rowspan='2' class='voucher_th'>
                                            Ticket Number(s)
                                        </th>
                                            <th rowspan='2'>
                                                Descriptions
                                            </th>
                                            <th rowspan='2'>
                                                Prices
                                            </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr></tr>";
                       foreach($tickets as $ticket)
                            {
                            	$html .= "<tr>
                                        <td align='center'>".
                                            $ticket->PassengerTicket_Id
                                        ."</td>
                                        <td align='center'>".
                                            $ticket->RoutePathWays_Place
                                        ."</td>
                                        <td align='right'> Php ".
                                           $ticket->PassengerTicket_Price
                                        ."</td>
                                    </tr>";
                            }// foreach payor
                       $html .=  "<tr>
                                        <td></td>
                                        <td align='center'>Online Service Fee</td>
                                        <td align='right'>Php $onlineFee</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td align='right'><b>---------------------------</b></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td align='right'>Total:</td>
                                        <td align='right'><b>Php $purchase->Purchase_TotalPrice</b></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br><br>
                            <br><br>
                            <table width='100%'>
                                <tr>
                                    <td>
                                        _____________________________
                                    </td>
                                    <td>
                                        _____________________________
                                    </td>
                                    <td>
                                        _____________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td align='center'>
                                        <b>Received By</b>
                                    </td>
                                    <td align='center'>
                                        <b>Paid By</b>
                                    </td>
                                    <td align='center'>
                                        <b>Authorized Personnel Signature</b>
                                    </td>
                                </tr>
                            </table>
                            <span>
                                <h4><b>Remarks</b></h4>
                            <span>
                            ____________________________________________________
                            <br><br>
                            <hr>
                        </div>
                    </body>
                    </html>";

    		$pdf = App::make('dompdf.wrapper');
    		$pdf->loadHTML($html)->setPaper('legal', 'portrait');
    		$pdf->save("./vouchers/$purchase->Purchase_Id - E-Voucher BRATS.pdf");

    	/*
    	 * SENDING PDF FILE
    	 */
    	Mail::send('pages.email.customer_email', ['customer_name' => $customer_name, 'transaction_number' => $purchase->Purchase_Id], 
    		function ($message) use ($customer, $purchase, $customer_name, $pdf) {
			$message->to($customer->OnlineCustomer_Email, $customer_name)
					->subject('Printable E-Voucher - Bus Reservation And Ticketing System')
					->attachData($pdf->output(), "$purchase->Purchase_Id - E-Voucher BRATS.pdf");
		});

		return view('pages.purchase.pdf', compact('customer', 'purchase', 'tickets', 'dispatch', 'title', 'onlineFee'));
    }

    public function save(Purchase $purchase, Customer $customer)
    {
    	# code...
    	$dispatch = Dispatch::select('Route_Name', 'TravelDispatch_Date', 'TravelSchedule_Time')
                              ->where('TravelDispatch_Id', '=', $purchase->TravelDispatch_Id)
                              ->join('travelschedule', 'traveldispatch.TravelSchedule_Id', '=', 'travelschedule.TravelSchedule_Id')
                              ->join('route', 'travelschedule.Route_Id', '=', 'route.Route_Id')
                              ->get();

        $tickets = Ticket::select('PassengerTicket_Price', 'RoutePathWays_Place', 'PassengerTicket_Id')
                                   ->where('Purchase_Id', '=',$purchase->Purchase_Id)
                                   ->join('routepathways', 'routepathways.RoutePathWays_Id', '=', 'passengerticket.RoutePathWays_Id')
                                   ->get();

       $onlineFee = $this->getOnlineFee(); 

    	$customer_name = $customer->OnlineCustomer_FirstName.' '.$customer->OnlineCustomer_MiddleName.' '.$customer->OnlineCustomer_LastName;
    	$departure_date = date_format(date_create($dispatch[0]->TravelDispatch_Date), 'm/d/Y');
    	$route = $dispatch[0]->Route_Name;
    	$valid = date_format(date_sub(date_create($dispatch[0]->TravelDispatch_Date), date_interval_create_from_date_string("1 day")), 'm/d/Y');
    	/*
    	 * CREATING PDF OUTPUT
    	 */
    		$html = "<!DOCTYPE html>
                    <html>
                    <head>
                        <title>
                            E-Voucher #$purchase->Purchase_Id Bus Reservation And Ticketing System Company
                        </title>
                        <link rel='stylesheet' href='./css/app.css'/>
                    </head>
                    <body>
                        <div class='brats-border'>
                            <table width='100%'>
                                <tr align='center'>
                                     <td>
                                         <h2>
                                             <b>
                                             <img src='./logo.png' width='100px' height='100px' align='center'>
                                                 Bus Reservation And Ticketing System Company
                                             </b>
                                         </h2>
                                     </td>
                                </tr>
                                <tr align='center'>
                                     <td>
                                        123 Saint Bernard Street, Brgy. Tibay, San Juan City Metro Manila Philippines
                                        <hr>
                                     </td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr align='center'>
                                    <td>
                                      <h1>
                                        <b><u>
                                            PAYMENT VOUCHER
                                        </u></b>
                                      </h1>
                                    </td>
                                </tr>
                            </table>
                            <table width='100%'>
                                <tr>
                                    <td>
                                        <b>PAYOR'S COPY<b><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Payment Date:</b> ________________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Transaction Number :</b> $purchase->Purchase_Id
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Account Number :</b> 000239010
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Payment Description :</b> 
                                    </td>
                                    <td>
                                        <b>Mode of Payment : </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        [ ] Full Payment 
                                        [ ] Half Payment
                                    </td>
                                    <td>
                                        [ ] Cashier
                                        [ ] Bank
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br><br>
                                        <b>Customer Name:</b> $customer_name
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Route:</b> $route
                                    </td>
                                    <td>
                                        <b>Date of Departure:</b> $departure_date
                                    </td>
                                </tr>
                            </table>
                            <br><br>
                            <table width='100%' border='1'>
                                <thead>
                                    <tr>
                                        <th rowspan='2' class='voucher_th'>
                                            Ticket Number(s)
                                        </th>
                                            <th rowspan='2'>
                                                Descriptions
                                            </th>
                                            <th rowspan='2'>
                                                Prices
                                            </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    </tr>
                            "; //$html

                            foreach($tickets as $ticket)
                            {
                            	$html .= "<tr>
                                        <td align='center'>".
                                            $ticket->PassengerTicket_Id
                                        ."</td>
                                        <td align='center'>".
                                            $ticket->RoutePathWays_Place
                                        ."</td>
                                        <td align='right'> Php ".
                                           $ticket->PassengerTicket_Price
                                        ."</td>
                                    </tr>";
                            }// foreach payor
                       $html .=  "<tr>
                                        <td></td>
                                        <td align='center'>Online Service Fee</td>
                                        <td align='right'>Php $onlineFee</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td align='right'><b>---------------------------</b></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td align='right'>Total:</td>
                                        <td align='right'><b>Php $purchase->Purchase_TotalPrice</b></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br><br>
                            <table width='100%'>
                                <tr>
                                    <td>
                                        _____________________________
                                    </td>
                                    <td>
                                        _____________________________
                                    </td>
                                    <td>
                                        _____________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td align='center'>
                                        <b>Received By</b>
                                    </td>
                                    <td align='center'>
                                        <b>Paid By</b>
                                    </td>
                                    <td align='center'>
                                        <b>Authorized Personnel Signature</b>
                                    </td>
                                </tr>
                            </table>
                            <span>
                                <h4><b>Remarks</b></h4>
                            <span>
                            ____________________________________________________
                            <br><br>
                            <hr>
                            <p>
                                <b><i>Instructions:</i></b>
                                <br><br>
                                    <i>
                                        This is your copy. Keep this in a safe place. This document is valid until <b>$valid</b>
                                        <br><br>

                                        If you are half-paid the teller will indicate to your copy of your installment in the remarks field.
                                        <br>
                                        Bring this voucher for refunds and cancellations of online reservations to the origin terminal.
                                        <br><br>
                                        I expressly agree to the Terms of Use, have read and understand the Terms & Agreement Policy, and confirm that the information that I have provided to the Bus Company website are true and correct to the best of my knowledge.  <br>My submission of this form will constitute my consent to the collection and use of my information and the transfer of information for processing and storage by the Bus Reservation And Ticketing System Company.  <br>Furthermore, I agree and understand that I am legally responsible for the information I entered in the Online Provincial Bus Reservation System and if I violate its Terms of Service my reservation may be revoked or my transaction will be voided.
                                    </i> 
                                    <br>
                            <p>
                            <hr>
                        </div>";
                        // CASHIER / TELLERS
                        $html .= "<div>
                             <table width='100%'>
                                <tr align='center'>
                                     <td>
                                         <h2>
                                             <b>
                                             <img src='./logo.png' width='100px' height='100px' align='center'>
                                                 Bus Reservation And Ticketing System Company
                                             </b>
                                         </h2>
                                     </td>
                                </tr>
                                <tr align='center'>
                                     <td>
                                        123 Saint Bernard Street, Brgy. Tibay, San Juan City Metro Manila Philippines
                                        <hr>
                                     </td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr align='center'>
                                    <td>
                                      <h1>
                                        <b><u>
                                            PAYMENT VOUCHER
                                        </u></b>
                                      </h1>
                                    </td>
                                </tr>
                            </table>
                            <table width='100%'>
                                <tr>
                                    <td>
                                        CASHIER'S / TELLER'S COPY
                                    </td>
                                    <td>
                                        ONLINE PAYMENT VOUCHER
                                    </td>
                                </tr>
                                <br><br>
                                <tr>
                                    <td>
                                        <b>Payment Date:</b> _____________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Transaction Number:</b> $purchase->Purchase_Id
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Account Number:</b> 000239010
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Payment Description :</b> 
                                    </td>
                                    <td>
                                        <b>Mode of Payment : </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        [ ] Full Payment 
                                        [ ] Half Payment
                                    </td>
                                    <td>
                                        [ ] Cashier
                                        [ ] Bank
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br><br>
                                        <b>Customer Name:</b> $customer_name
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Route:</b> $route
                                    </td>
                                    <td>
                                        <b>Date of Departure:</b> $departure_date
                                    </td>
                                </tr>
                            </table>
                            <br><br>
                            <table width='100%' border='1'>
                                <thead>
                                    <tr>
                                        <th rowspan='2' class='voucher_th'>
                                            Ticket Number(s)
                                        </th>
                                            <th rowspan='2'>
                                                Descriptions
                                            </th>
                                            <th rowspan='2'>
                                                Prices
                                            </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr></tr>";
                       foreach($tickets as $ticket)
                            {
                            	$html .= "<tr>
                                        <td align='center'>".
                                            $ticket->PassengerTicket_Id
                                        ."</td>
                                        <td align='center'>".
                                            $ticket->RoutePathWays_Place
                                        ."</td>
                                        <td align='right'> Php ".
                                           $ticket->PassengerTicket_Price
                                        ."</td>
                                    </tr>";
                            }// foreach payor
                       $html .=  "<tr>
                                        <td></td>
                                        <td align='center'>Online Service Fee</td>
                                        <td align='right'>Php $onlineFee</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td align='right'><b>---------------------------</b></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td align='right'>Total:</td>
                                        <td align='right'><b>Php $purchase->Purchase_TotalPrice</b></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br><br>
                            <br><br>
                            <table width='100%'>
                                <tr>
                                    <td>
                                        _____________________________
                                    </td>
                                    <td>
                                        _____________________________
                                    </td>
                                    <td>
                                        _____________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td align='center'>
                                        <b>Received By</b>
                                    </td>
                                    <td align='center'>
                                        <b>Paid By</b>
                                    </td>
                                    <td align='center'>
                                        <b>Authorized Personnel Signature</b>
                                    </td>
                                </tr>
                            </table>
                            <span>
                                <h4><b>Remarks</b></h4>
                            <span>
                            ____________________________________________________
                            <br><br>
                            <hr>
                        </div>
                    </body>
                    </html>";
        /*
		 * returning download link. automatically downloads PDF FILE
        */
    	$pdf = App::make('dompdf.wrapper');
    	$pdf->loadHTML($html)->setPaper('legal', 'portrait');
    	return $pdf->download("$purchase->Purchase_Id - E-Voucher BRATS.pdf");
    }

    public function printDocument(Purchase $purchase, Customer $customer)
    {
    	$dispatch = Dispatch::select('Route_Name', 'TravelDispatch_Date', 'TravelSchedule_Time')
                              ->where('TravelDispatch_Id', '=', $purchase->TravelDispatch_Id)
                              ->join('travelschedule', 'traveldispatch.TravelSchedule_Id', '=', 'travelschedule.TravelSchedule_Id')
                              ->join('route', 'travelschedule.Route_Id', '=', 'route.Route_Id')
                              ->get();

        $tickets = Ticket::select('PassengerTicket_Price', 'RoutePathWays_Place', 'PassengerTicket_Id')
                                   ->where('Purchase_Id', '=',$purchase->Purchase_Id)
                                   ->join('routepathways', 'routepathways.RoutePathWays_Id', '=', 'passengerticket.RoutePathWays_Id')
                                   ->get();

       $onlineFee = $this->getOnlineFee(); 
    	$customer_name = $customer->OnlineCustomer_FirstName.' '.$customer->OnlineCustomer_MiddleName.' '.$customer->OnlineCustomer_LastName;
    	$departure_date = date_format(date_create($dispatch[0]->TravelDispatch_Date), 'm/d/Y');
    	$route = $dispatch[0]->Route_Name;
    	$valid = date_format(date_sub(date_create($dispatch[0]->TravelDispatch_Date), date_interval_create_from_date_string("1 day")), 'm/d/Y');
    	/*
    	 * CREATING PDF FILE
    	 */
    		$html = "<!DOCTYPE html>
                    <html>
                    <head>
                        <title>
                            E-Voucher #$purchase->Purchase_Id Bus Reservation And Ticketing System Company
                        </title>
                        <link rel='stylesheet' href='./css/app.css'/>
                    </head>
                    <body>
                        <div class='brats-border'>
                            <table width='100%'>
                                <tr align='center'>
                                     <td>
                                         <h2>
                                             <b>
                                             <img src='./logo.png' width='100px' height='100px' align='center'>
                                                 Bus Reservation And Ticketing System Company
                                             </b>
                                         </h2>
                                     </td>
                                </tr>
                                <tr align='center'>
                                     <td>
                                        123 Saint Bernard Street, Brgy. Tibay, San Juan City Metro Manila Philippines
                                        <hr>
                                     </td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr align='center'>
                                    <td>
                                      <h1>
                                        <b><u>
                                            PAYMENT VOUCHER
                                        </u></b>
                                      </h1>
                                    </td>
                                </tr>
                            </table>
                            <table width='100%'>
                                <tr>
                                    <td>
                                        <b>PAYOR'S COPY<b><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Payment Date:</b> ________________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Transaction Number :</b> $purchase->Purchase_Id
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Account Number :</b> 000239010
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Payment Description :</b> 
                                    </td>
                                    <td>
                                        <b>Mode of Payment : </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        [ ] Full Payment 
                                        [ ] Half Payment
                                    </td>
                                    <td>
                                        [ ] Cashier
                                        [ ] Bank
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br><br>
                                        <b>Customer Name:</b> $customer_name
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Route:</b> $route
                                    </td>
                                    <td>
                                        <b>Date of Departure:</b> $departure_date
                                    </td>
                                </tr>
                            </table>
                            <br><br>
                            <table width='100%' border='1'>
                                <thead>
                                    <tr>
                                        <th rowspan='2' class='voucher_th'>
                                            Ticket Number(s)
                                        </th>
                                            <th rowspan='2'>
                                                Descriptions
                                            </th>
                                            <th rowspan='2'>
                                                Prices
                                            </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    </tr>
                            "; //$html

                            foreach($tickets as $ticket)
                            {
                            	$html .= "<tr>
                                        <td align='center'>".
                                            $ticket->PassengerTicket_Id
                                        ."</td>
                                        <td align='center'>".
                                            $ticket->RoutePathWays_Place
                                        ."</td>
                                        <td align='right'> Php ".
                                           $ticket->PassengerTicket_Price
                                        ."</td>
                                    </tr>";
                            }// foreach payor
                       $html .=  "<tr>
                                        <td></td>
                                        <td align='center'>Online Service Fee</td>
                                        <td align='right'>Php $onlineFee</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td align='right'><b>---------------------------</b></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td align='right'>Total:</td>
                                        <td align='right'><b>Php $purchase->Purchase_TotalPrice</b></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br><br>
                            <table width='100%'>
                                <tr>
                                    <td>
                                        _____________________________
                                    </td>
                                    <td>
                                        _____________________________
                                    </td>
                                    <td>
                                        _____________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td align='center'>
                                        <b>Received By</b>
                                    </td>
                                    <td align='center'>
                                        <b>Paid By</b>
                                    </td>
                                    <td align='center'>
                                        <b>Authorized Personnel Signature</b>
                                    </td>
                                </tr>
                            </table>
                            <span>
                                <h4><b>Remarks</b></h4>
                            <span>
                            ____________________________________________________
                            <br><br>
                            <hr>
                            <p>
                                <b><i>Instructions:</i></b>
                                <br><br>
                                    <i>
                                        This is your copy. Keep this in a safe place. This document is valid until <b>$valid</b>
                                        <br><br>

                                        If you are half-paid the teller will indicate to your copy of your installment in the remarks field.
                                        <br>
                                        Bring this voucher for refunds and cancellations of online reservations to the origin terminal.
                                        <br><br>
                                        I expressly agree to the Terms of Use, have read and understand the Terms & Agreement Policy, and confirm that the information that I have provided to the Bus Company website are true and correct to the best of my knowledge.  <br>My submission of this form will constitute my consent to the collection and use of my information and the transfer of information for processing and storage by the Bus Reservation And Ticketing System Company.  <br>Furthermore, I agree and understand that I am legally responsible for the information I entered in the Online Provincial Bus Reservation System and if I violate its Terms of Service my reservation may be revoked or my transaction will be voided.
                                    </i> 
                                    <br>
                            <p>
                            <hr>
                        </div>";
                        // CASHIER / TELLERS
                        $html .= "<div>
                             <table width='100%'>
                                <tr align='center'>
                                     <td>
                                         <h2>
                                             <b>
                                             <img src='./logo.png' width='100px' height='100px' align='center'>
                                                 Bus Reservation And Ticketing System Company
                                             </b>
                                         </h2>
                                     </td>
                                </tr>
                                <tr align='center'>
                                     <td>
                                        123 Saint Bernard Street, Brgy. Tibay, San Juan City Metro Manila Philippines
                                        <hr>
                                     </td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr align='center'>
                                    <td>
                                      <h1>
                                        <b><u>
                                            PAYMENT VOUCHER
                                        </u></b>
                                      </h1>
                                    </td>
                                </tr>
                            </table>
                            <table width='100%'>
                                <tr>
                                    <td>
                                        CASHIER'S / TELLER'S COPY
                                    </td>
                                    <td>
                                        ONLINE PAYMENT VOUCHER
                                    </td>
                                </tr>
                                <br><br>
                                <tr>
                                    <td>
                                        <b>Payment Date:</b> _____________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Transaction Number:</b> $purchase->Purchase_Id
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Account Number:</b> 000239010
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Payment Description :</b> 
                                    </td>
                                    <td>
                                        <b>Mode of Payment : </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        [ ] Full Payment 
                                        [ ] Half Payment
                                    </td>
                                    <td>
                                        [ ] Cashier
                                        [ ] Bank
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br><br>
                                        <b>Customer Name:</b> $customer_name
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Route:</b> $route
                                    </td>
                                    <td>
                                        <b>Date of Departure:</b> $departure_date
                                    </td>
                                </tr>
                            </table>
                            <br><br>
                            <table width='100%' border='1'>
                                <thead>
                                    <tr>
                                        <th rowspan='2' class='voucher_th'>
                                            Ticket Number(s)
                                        </th>
                                            <th rowspan='2'>
                                                Descriptions
                                            </th>
                                            <th rowspan='2'>
                                                Prices
                                            </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr></tr>";
                       foreach($tickets as $ticket)
                            {
                            	$html .= "<tr>
                                        <td align='center'>".
                                            $ticket->PassengerTicket_Id
                                        ."</td>
                                        <td align='center'>".
                                            $ticket->RoutePathWays_Place
                                        ."</td>
                                        <td align='right'> Php ".
                                           $ticket->PassengerTicket_Price
                                        ."</td>
                                    </tr>";
                            }// foreach payor
                       $html .=  "<tr>
                                        <td></td>
                                        <td align='center'>Online Service Fee</td>
                                        <td align='right'>Php $onlineFee</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td align='right'><b>---------------------------</b></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td align='right'>Total:</td>
                                        <td align='right'><b>Php $purchase->Purchase_TotalPrice</b></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br><br>
                            <br><br>
                            <table width='100%'>
                                <tr>
                                    <td>
                                        _____________________________
                                    </td>
                                    <td>
                                        _____________________________
                                    </td>
                                    <td>
                                        _____________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td align='center'>
                                        <b>Received By</b>
                                    </td>
                                    <td align='center'>
                                        <b>Paid By</b>
                                    </td>
                                    <td align='center'>
                                        <b>Authorized Personnel Signature</b>
                                    </td>
                                </tr>
                            </table>
                            <span>
                                <h4><b>Remarks</b></h4>
                            <span>
                            ____________________________________________________
                            <br><br>
                            <hr>
                        </div>
                    </body>
                    </html>";
        /*
		 * Outputting the PDF FILE to the BROWSER
        */
        $pdf = App::make('dompdf.wrapper');
    	$pdf->loadHTML($html)->setPaper('legal', 'portrait');
    	return $pdf->stream("$purchase->Purchase_Id - E-Voucher BRATS.pdf");
    }

    // function for getting the amount 
    // -> added to model for shortcut
    public function getOnlineFee()
    {
        $fee = OnlineFee::select('OnlineReservationFee_Amount')
                        ->orderBy('OnlineReservationFee_Id', 'desc')
                        ->take(1)
                        ->get();

        return $fee[0]->OnlineReservationFee_Amount;
    }
}