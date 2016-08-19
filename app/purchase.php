<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
    public $primaryKey = "Purchase_Id";
 	public $table = "purchase";
 	public $timestamps = false;

 	protected $fillable = [
 							'PurchaseType_Id', 'TravelDispatch_Id', 'Purchase_TotalPrice',
 							'Purchase_Date', 'Terminal_Id'
 							];

 	public function purchase_types()
 	{
 		return $this->belongsTo(purchase_type::class, 'PurchaseType_Id', 'PurchaseType_Id');
 	}

 	public function travel_dispatches()
 	{
 		return $this->belongsTo(travel_dispatch::class, 'TravelDispatch_Id', 'TravelDispatch_Id');
 	}

 	public function terminals()
 	{
 		return $this->belongsTo(terminal::class, 'Terminal_Id', 'Terminal_Id');
 	}

 	public function online_customers()
 	{
 		return $this->hasMany(online_customer::class, 'OnlineCustomer_Id', 'OnlineCustomer_Id');
 	}

 	public function passenger_tickets()
 	{
 		return $this->hasMany(passenger_ticket::class, 'PassengerTicket_Id', 'PassengerTicket_Id');
 	}

 	public function payments()
 	{
 		return $this->hasMany(payment::class, 'Payment_Id', 'Payment_Id');
 	}
}
