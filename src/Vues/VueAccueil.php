<?php

namespace App\Vues; 

class VueAccueil extends Vue{

    public function __construct($c, $rq){
        parent::__construct("", $c, $rq);
    }

    public function linkCss() : array{
        return ["pages/accueil.css"];
    }
    
    public function createContent() : string{
        $titre = "Accueil";
        if(isset($_SESSION["login"])) $name = $_SESSION["login"];
        $html = <<<HTML
            <h1 class="title"> Wish liste </h1>
            <div style="text-align:center;">
                <img src="img/gift.png" alt="Image cadeau" style="width:390px;">
            </div>
            <h2> Bienvenue sur le site qui permet de créer des listes de souhaits ! </h2>
        HTML;
        return $html;
    }
}