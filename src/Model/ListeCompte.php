<?php 

namespace App\Model; 

class ListeCompte extends \Illuminate\Database\Eloquent\Model {
    
    protected $table = 'listecompte';

    protected $primaryKey = "loginCompte";

    public $timestamps = false;

}