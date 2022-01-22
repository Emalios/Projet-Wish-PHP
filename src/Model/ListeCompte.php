<?php 

namespace App\Model; 

class ListeCompte extends \Illuminate\Database\Eloquent\Model {
    
    protected $table = 'listecompte';

    protected $primaryKey = "idCompte";

    public $timestamps = false;

    public static function getParticipation($idListe) {
        $participation = ListeCompte::where("idCompte", "=", $_SESSION["userId"])->where("idListe", "=", $idListe)->first();
        echo $participation;
        return $participation ?? null; 
    }

}