<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
class EmailController extends Controller
{
    public function send(Request $request)
    {
    	/*
		 * TODO : GET FULL NAME OF THE PAYOR
		 *		  GET THE TRANSACTION NUMBER
		 *	      PROVIDE A TRACKING OF FILE PATH OF THE PDF
		 *	      COMPLETE customer_email.blade.php TEMPLATE
    	*/

    	Mail::send('pages.email.customer_email', ['VARIABLE_TO_TEMPLATE' => 'VALUE'], function ($message) {
			$message->to($request->customer_email, $request->customer_name)
					->subject('Printable E-Voucher - Bus Reservation And Ticketing System')
					->attach('PDF_FILE_PATH');
		});
    }
}
