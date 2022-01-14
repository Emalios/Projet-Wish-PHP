<?php

namespace App\Vues; 

class VueAccueil extends Vue{

    public function __construct(){

    }

    public function linkCss() : string{
        return "";
    }
    
    public function createContent() : string{
        $titre = "Accueil";
        $html = <<<HTML
            <h1> Liste : $titre </h1>
            <a href="/liste/1"> Liste 1 </p>
            <a href="/ajouter-liste"> Ajouter une liste </p>
        HTML;
        return $html;
    }
}