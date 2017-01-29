<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\terminal as Terminal;
use App\utilities_company as Utilities;
use App\test_image as Image;

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
}
