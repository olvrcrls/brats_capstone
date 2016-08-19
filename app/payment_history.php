<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class payment_history extends Model
{
    public $primaryKey = "PaymentHistory_Id";
 	public $table = "paymenthistory";
 	public $timestamps = false;

 	public $fillable = [
 			'Payment_Id', 'PaymentHistory_Amount', 'PaymentHistory_Date', 'Terminal_Id'
 	];

 	public function payments()
 	{
 		return $this->belongsTo(payment::class, 'Payment_Id', 'Payment_Id');
 	}

 	public function terminals()
 	{
 		return $this->belongsTo(terminal::class, 'Terminal_Id', 'Terminal_Id');
 	}
}
