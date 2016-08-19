<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class purchase_type extends Model
{
	public $primaryKey = "PurchaseType_Id";
 	public $table = "purchasetype";
 	public $timestamps = false;    

 	public function purchases()
 	{
 		return $this->hasMany(purchase::class, 'Purchase_Id', 'Purchase_Id');
 	}
}
