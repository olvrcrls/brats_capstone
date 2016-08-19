<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class online_customer extends Model
{
    public $primaryKey = "OnlineCustomer_Id";
 	public $table = "onlinecustomer";
 	public $timestamps = false;

 	public $fillable = ['OnlineCustomer_FirstName', 'OnlineCustomer_LastName', 'OnlineCustomer_MiddleName', 'OnlineCustomer_Email', 											'OnlineCustomer_ContactNumber', 'OnlineCustomer_DateOfReservation', 'Purchase_Id'
 						];

 	public function purchases()
 	{
 		return $this->belongsTo(purchase::class, 'Purchase_Id', 'Purchase_Id');
 	}
}
