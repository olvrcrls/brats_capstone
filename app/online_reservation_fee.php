<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class online_reservation_fee extends Model
{
    public $timestamps = false;
    public $primaryKey = 'OnlineReservationFee_Id';
    public $table = 'onlinereservationfee';
    
}
