<?php

namespace App\Vues; 

class VueCreateurs extends Vue{

    private $listesCreateurs; 

    public function __construct($listesCreateurs, $c, $rq){
        parent::__construct("", $c, $rq);	
        $this->listesCreateurs = $listesCreateurs;
    }

    public function linkCss() : array{
        $links = ['pages/liste.css', 'elements/formulaire.css', 'elements/inputs.css'];
        return $links;
    }
    
    public function createContent() : string{
        $res = ""; 
        foreach($this->listesCreateurs as $createur){
            if($createur != null)
                $res .= "<p> - " . $createur->login .  "</p>";
        }
        return <<<HTML
            <h1> Listes des créateurs de listes publiques </h1>
            <h3 style="text-align: center;"> Vous voyez ici le login de chaque utilisateurs ayant créé une liste publique </h3>
            <br>
            $res
        HTML;
    }
}