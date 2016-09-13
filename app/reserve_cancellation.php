<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class reserve_cancellation extends Model
{
    protected $table = 'reservecancellation';
    protected $primaryKey = 'ReserveCollection_Id';
    protected $fillable = ['ReserveCancellation_DateOfCancelation', 'ReserveCancellationPercentage_Id', 'ReserveCancellation_Reason', 
    						'ReserveCancellation_AmountReturn', 'ReserveCancellation_Status'
    					  ];
}
