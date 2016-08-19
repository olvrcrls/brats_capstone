<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    public $primaryKey = "Payment_Id";
 	public $table = "payment";
 	public $timestamps = false;

 	public $fillable = [
 			'Purchase_Id', 'PaymentStatus_Id', 'Terminal_Id'
 	];

 	public function purchases()
 	{
 		return $this->belongsTo(purchase::class, 'Purchase_Id', 'Purchase_Id');
 	}

 	public function payment_statuses()
 	{
 		return $this->belongsTo(payment_status::class, 'PaymentStatus_Id', 'PaymentStatus_Id');
 	}

 	public function terminals()
 	{
 		return $this->belongsTo(terminal::class, 'Terminal_Id', 'Terminal_Id');
 	}

 	public function payment_histories()
 	{
 		return $this->hasMany(payment_history::class, 'PaymentHistory_Id', 'PaymentHistory_Id');
 	}
}
