<?php 

namespace App\Vues; 

use Style\loadCss;
use App\Helpers\Elements as Elements; 

class VueAjoutListe extends Vue{

    public function __construct($c, $rq){
        parent::__construct("", $c, $rq);	
    }

    public function linkCss() : string{
        $links = ['elements/formulaire.css', 'elements/inputs.css', 'elements/basic.css'];
        return loadCss::toHtml($links);
    }

    public function createContent() : string{ 
        $date = Elements::dateDropDown(); 
        $html = <<<HTML
            <h1> Ajouter une liste : </h1>
            <form action="/ajouter-liste" method="POST" class="center-right-form">
                <div class="center-form-inside">
                    <label for="titre" class="label-primary">Titre</label>
                    <input type="text" class="text" value="" placeholder="Entrez un titre" name="titre">
                    <label for="mdp" class="label-primary">Liste publique</label>
                    <input type="checkbox" name="publique">
                    <label for="mdp" class="label-primary">Description</label>
                    <textarea type="textarea" class="text" value="" placeholder="Entrez une description" name="desc"></textarea>
                    $date
                    <button type="submit" class="second-button">Enregistrer</button>
                </div>
            </form>
        HTML; 
        return $html;
    }
}