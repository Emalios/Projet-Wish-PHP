<?php

namespace App\Vues; 

class VueCompteParticipation extends Vue{

    private $compte, $listes, $participations; 

    /**
     * Listes est un tableau associatif qui a une liste affecte un montant de participation
     */
    public function __construct($compte, $listes, $participations, $c, $rq){
        parent::__construct("", $c, $rq);	
        $this->compte = $compte; 
        $this->listes = $listes; 
        $this->participations = $participations;
    }

    public function linkCss() : string{
        return "";
    }
    
    public function createContent() : string{
        $login = $_SESSION['login'];
        $listesParticipes = ""; 
        for($i = 0; $i < count($this->listes); $i++){
            $liste = $this->listes[$i];
            $participation = $this->participations[$i]; 
            $link = "/liste/" . $liste["token"]; 
            $nom = $liste["titre"]; 
            $participation = "Participation : " . $participation->participation; 
            $listesParticipes .= "<a href=\"$link\"> $nom </a> <p> $participation </p> <br>";
        }
        $html = <<<HTML
            <h1> $login </h1>
            <a href="/mon-compte">Changer mot de passe</a>
            <br>
            $listesParticipes
        HTML;
        return $html;
    }
}