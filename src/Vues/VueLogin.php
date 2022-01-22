<?php

namespace App\Vues; 

class VueLogin extends Vue{

    public function __construct($c, $rq){
        parent::__construct("", $c, $rq);	
    }

    public function linkCss() : array{
        return ["pages/connexion.css", "elements/formulaire.css"];
    }
    
    public function createContent() : string{
        $link = $this->container->router->pathFor('login');
        $html = <<<HTML
            <h1 class="subtitle"> Connexion </h1>
            <div class="center-connexion">
                <form action="$link" method="POST" class="center-right-form">
                    <label for="email" class="label-primary">Email</label>
                    <input type="text" class="text" value="" placeholder="Entrez un login" name="email">
                    <label for="mdp" class="label-primary">Mot de passe</label>
                    <input type="password" class="text" value="" placeholder="Entrez un mot de passe" name="mdp">
                    <button type="submit" class="second-button">Se connecter</button>
                </form>
            </div>
        HTML;
        return $html;
    }
}