<?php

namespace App\Vues; 

class VueCreateurs extends Vue{

    private $listesCreateurs; 

    public function __construct($listesCreateurs, $c, $rq){
        parent::__construct("", $c, $rq);	
        $this->listesCreateurs = $listesCreateurs;
    }

    public function linkCss() : string{
        return "";
    }
    
    public function createContent() : string{
        $res = ""; 
        foreach($this->listesCreateurs as $createur){
            $res .= "<p>" . $createur->login .  "</p>";
        }
        return $res;
    }
}