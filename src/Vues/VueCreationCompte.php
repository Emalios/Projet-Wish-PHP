<?php 

namespace App\Vues; 

use Style\loadCss;
use App\Helpers\Elements as Elements; 

class VueCreationCompte extends Vue{

    private $messageErreur; 

    public function __construct($messageErreur, $c, $rq){
        parent::__construct("", $c, $rq);	
        $this->messageErreur = $messageErreur; 
    } 

    public function linkCss() : string{
        $links = ['elements/formulaire.css', 'elements/inputs.css', 'elements/basic.css'];
        return loadCss::toHtml($links);
    }

    public function createContent() : string{ 
        $html = <<<HTML
            <p> $this->messageErreur </p>
            <h1> Ajouter un compte : </h1>
            <form action="/creer-compte" method="POST" class="center-right-form">
                <div class="center-form-inside">
                    <label for="titre" class="label-primary">Login</label>
                    <input type="text" class="text" value="" placeholder="Entrez un login" name="login">
                    <label for="mdp" class="label-primary">Mot de passe</label>
                    <input type="password" class="text" value="" placeholder="Entrez un mot de passe" name="mdp">
                    <button type="submit">Créer un compte</button>
                </div>
            </form>
        HTML; 
        return $html;
    }
}