<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class payment_status extends Model
{
    public $primaryKey = "PaymentStatus_Id";
 	public $table = "paymentstatus";
 	public $timestamps = false;

 	public function payments()
 	{
 		return $this->hasMany(payment::class, 'Payment_Id', 'Payment_Id');
 	}
}
