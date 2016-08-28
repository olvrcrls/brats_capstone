<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class online_reservation_fee extends Model
{
    public $timestamps = false;
    public $primaryKey = 'OnlineReservationFee_Id';
    public $table = 'onlinereservationfee';

    public function getOnlineFee()
    {
        $fee = App\online_reservation_fee::select('OnlineReservationFee_Amount')
                        ->orderBy('OnlineReservationFee_Id', 'desc')
                        ->take(1)
                        ->get();

        return $fee[0]->OnlineReservationFee_Amount;
    }
    
}
