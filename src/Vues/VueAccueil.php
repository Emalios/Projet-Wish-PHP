<?php

namespace App\Vues; 

class VueAccueil extends Vue{

    public function __construct($c, $rq){
        parent::__construct("", $c, $rq);
    }

    public function linkCss() : string{
        return "";
    }
    
    public function createContent() : string{
        $titre = "Accueil";
        if(isset($_SESSION["login"])) $name = $_SESSION["login"];
        $html = <<<HTML
            <h1> Wish liste </h1>
            <a href="/liste/1"> Liste 1 </p>
            <a href="/ajouter-liste"> Ajouter une liste </p>
        HTML;
        return $html;
    }
}