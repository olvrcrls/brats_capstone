<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class purchase_type extends Model
{
	public $primaryKey = "PurchaseType_Id";
 	public $table = "purchasetype";
 	public $timestamps = false;    

 	public function purchases()
 	{
 		return $this->hasMany(purchase::class, 'Purchase_Id', 'Purchase_Id');
 	}

 	public static function getId($name)
 	{
 		try
 		{
	 		$id = DB::table('purchasetype')->select('PurchaseType_Id')
	 				  ->where('PurchaseType_Name', '=', $name)
	 				  ->get();
	 		return $id[0]->PurchaseType_Id;
	 	}
	 	catch (Exception $e)
	 	{
	 		return null;
	 	}
 	}
}
