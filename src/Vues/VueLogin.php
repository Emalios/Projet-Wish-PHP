<?php

namespace App\Vues; 

class VueLogin extends Vue{

    public function __construct($c, $rq){
        parent::__construct("", $c, $rq);	
    }

    public function linkCss() : string{
        return "";
    }
    
    public function createContent() : string{
        $titre = "Login";
        $html = <<<HTML
            <form action="/login" method="POST" class="center-right-form">
                <div class="center-form-inside">
                    <label for="titre" class="label-primary">Login</label>
                    <input type="text" class="text" value="" placeholder="Entrez un login" name="login">
                    <label for="mdp" class="label-primary">Mot de passe</label>
                    <input type="password" class="text" value="" placeholder="Entrez un mot de passe" name="mdp">
                    <button type="submit">Se connecter</button>
                </div>
            </form>
        HTML;
        return $html;
    }
}