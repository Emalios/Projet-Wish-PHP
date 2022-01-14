<?php

namespace App\Vues; 

class VueCompte extends Vue{

    private $compte; 

    public function __construct($compte, $c, $rq){
        parent::__construct("", $c, $rq);	
        $this->compte = $compte; 
    }

    public function linkCss() : string{
        return "";
    }
    
    public function createContent() : string{
        $login = $_SESSION['login'];
        $html = <<<HTML
            <h1> $login </h1>
            <form action="/modifier-compte" method="POST" class="center-right-form">
                <div class="center-form-inside">
                    <label for="titre" class="label-primary">Changer le mot de passe</label>
                    <input type="password" class="text" value="" placeholder="Entrez un mot de passe" name="mdp">
                    <button type="submit">Enregistrer</button>
                </div>
                <a href="/mon-compte?query=participations">Voir mes participations</a>
                <a href="/mon-compte?query=supprimer">Supprimer</a>
            </form>
        HTML;
        return $html;
    }
}