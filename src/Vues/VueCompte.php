<?php

namespace App\Vues; 

class VueCompte extends Vue{

    private $compte; 

    private $query;

    public function __construct($compte, $c, $rq, $query = ""){
        parent::__construct("", $c, $rq);	
        $this->compte = $compte; 
        $this->query = $query; 
    }

    public function linkCss() : array{
        return ["pages/connexion.css"];
    }
    
    public function createContent() : string{
        $login = $_SESSION['login'];
        switch($this->query){
            case "":
                $content = $this->formPassword();
                break;
            case "participation":
                $content = $this->getParticipations(); 
                break;
            case "joindre":
                $content = $this->getParticipations(); 
                break;
        }
        $html = <<<HTML
            <div class="center-connected">
                <div class="center-gestion">
                    <h1 class="second-subtitle pseudo"> $login </h1>
                    <a href="/mon-compte">Changer le mot de passe</a>
                    <a href="/mon-compte?query=participations">Voir mes participations</a>
                    <a href="/mon-compte?query=joindre">Joindre une liste</a>
                    <a href="/mon-compte?query=supprimer">Supprimer mon compte</a>
                    <a href="/mon-compte?query=deconnexion"  class="lastLien">Se déconnecter</a>
                </div>
                <div class="center-data">
                    $content 
                </div>
            </div>
        HTML;
        return $html;
    }

    private function formPassword(){
        $login =  $_SESSION["login"];
        return <<<HTML
                <h2 class="second-subtitle"> $login </h1>
                <form action="/modifier-compte" method="POST" class="center-right-form">
                    <div class="center-form-inside">
                        <label for="titre" class="label-primary">Changer le mot de passe</label>
                        <input type="password" class="text" value="" placeholder="Entrez un mot de passe" name="mdp">
                        <button type="submit">Enregistrer</button>
                    </div>
                </form>
        HTML;
    }

    private function getParticipations(){
        $login =  $_SESSION["login"];
        $html = <<<HTML
            <h1> $login </h1>
            <h3> Vous pouvez voir ici toutes vos participations à des cagnottes </h3>
        HTML;
        return $html;
    }


    private function joindreListe(){
        $login =  $_SESSION["login"];
        $html = <<<HTML
            <h1> $login </h1>
            <h3> Vous pouvez joindre une liste à votre compte si vous donnez son token de modification </h3>
        HTML;
        return $html;
    }

}