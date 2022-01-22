<?php 

namespace App\Model;

use App\Model\Compte as Compte; 


class Liste extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'liste';

    protected $primaryKey = 'no';

    public $timestamps = false;

    public static function getNumberOfList(){
        return Liste::count();
    }

    public static function isOwner($list){
        if(!Compte::isConnected())
            return (isset($_COOKIE[$list->token]) && $_COOKIE[$list->token] == $list->tokenModification);
        return  $list->createur_id == $_SESSION['userId']; 
    }
}