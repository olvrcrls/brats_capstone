<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class toddler_passenger extends Model
{
    public $primaryKey = "ToddlerPassenger_Id";
 	public $table = "toddlerpassenger";
 	public $timestamps = false;

 	public function terminals()
 	{
 		return $this->belongsTo(terminal::class, 'Terminal_Id', 'Terminal_Id');
 	}

 	public function purchases()
 	{
 		return $this->belongsTo(purchase::class, 'Purchase_Id', 'Purchase_Id');
 	}
}
