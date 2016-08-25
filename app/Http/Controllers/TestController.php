<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests;
use App;
class TestController extends Controller
{
	public function pdf()
	{
		# code...
    	$pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>Test</h1>');
        return $pdf->stream('test.pdf');
    }

    public function email()
    {
    	Mail::send('test.email', ['name' => 'Oliver'], function ($message) {
		$message->to('oliverpascualcarlos@gmail.com', 'Oliver Carlos')->subject('Test');
	});

    	return('Success');

	// Mail::send('E-MAIL TEMPLATE', ['VARIABLE_TO_TEMPLATE' => 'VALUE'], function ($message) {
	// 	$message->to('RECEIVER@MAIL.COM', 'NAME OF THE RECEIVER')->subject('SUBJECT');
	// });
    }
}
