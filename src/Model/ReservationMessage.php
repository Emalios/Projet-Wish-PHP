<?php 

namespace App\Model;


class ReservationMessage extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'reservationmessage';

    protected $primaryKey = 'idMessage';

    public $timestamps = false;

}