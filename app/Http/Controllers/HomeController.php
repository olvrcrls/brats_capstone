<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\terminal as Terminal;
use App\utilities_company as Utilities;
use App\test_image as Image;
use App\reserve_cancellation_percentage as Percentage;
use App\days_span_to_reserve as ReservationDay;
use App\reservation_days_to_void as Void;

class HomeController extends Controller
{
    /**
    * Reloads the list of the terminals and then
    * reloads the homepage.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
    	 $terminals = Terminal::select('Terminal_Id', 'Terminal_Name')
    	 						->where('Record_Status', '=', 'Active')
    	 						->get();

    	 return view('welcome', compact('terminals'));
    }

    /**
    * There is no available trip.
    * Reloads the list of the terminals and then
    * reloads the homepage.
    *
    * @return \Illuminate\Http\Response
    */
    public function fail()
    {
    	$terminals = Terminal::select('Terminal_Id', 'Terminal_Name')
    						 ->where('Record_Status', '=', 'Active')
    						 ->get();
    	$no_date = true;
    	return view('welcome', compact('terminals', 'no_date'));
    }

    public function termsAndAgreement()
    {
        $percentages = Percentage::select('ReserveCancellationPercentage_NumberOfDays', 'ReserveCancellationPercentage_PercentageReturn')
                              ->get();
        $totalDays = Percentage::count();
        $days = ReservationDay::select('DaysSpanToReserve_Days')->orderBy('DaysSpanToReserve_Id', 'desc')->take(1)->get();
        $voidDay = Void::select('ReservationDaysToVoid_Days')->orderBy('ReservationDaysToVoid_Id', 'desc')->take(1)->get();
        if (!$days->count() || !$voidDay->count())
        {
            $days = 0;
            $voidDay = 0;
            return view('pages.purchase.ta', ['title' => 'Terms And Agreement - Bus Reservation And Ticketing System', 'percentages' => $percentages,
                                          'totalDays' => $totalDays, 'days' => $days, 'voidDay' => $voidDay]);
        }
    return view('pages.purchase.ta', [
        'title' => 'Terms And Agreement - Bus Reservation And Ticketing System', 'percentages' => $percentages,
        'totalDays' => $totalDays, 
        'days' => $days[0]->DaysSpanToReserve_Days , 
        'voidDays' => $voidDay[0]->ReservationDaysToVoid_Days
        ]);
    }
}
