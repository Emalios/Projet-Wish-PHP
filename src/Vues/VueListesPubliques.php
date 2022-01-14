<?php

namespace App\Vues; 

class VueListesPubliques extends Vue{

    private $listes; 

    public function __construct($listes, $c, $rq){
        parent::__construct("", $c, $rq);	
        $this->listes = $listes;
    }

    public function linkCss() : string{
        return "";
    }
    
    public function createContent() : string{
        $res = ""; 
        foreach($this->listes as $liste){
            $res .= "<p> " . $liste->titre . "</p><a href='/liste/" . $liste->token . "'>Voir</a><br>";
        }
        return $res;
    }
}