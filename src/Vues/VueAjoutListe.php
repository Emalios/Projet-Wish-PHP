<?php 

namespace App\Vues; 

use Style\loadCss;

class VueAjoutListe extends Vue{

    public function __construct(){ 
    } 

    public function linkCss() : string{
        $links = ['elements/formulaire.css', 'elements/inputs.css', 'elements/basic.css'];
        return loadCss::toHtml($links);
    }

    public function createContent() : string{ 
        echo var_dump($_POST);
        $html = <<<HTML
            <h1> Ajouter une liste : </h1>
            <form action="" method="POST" class="center-right-form">
                <div class="center-form-inside">
                    <label for="titre" class="label-primary">Titre</label>
                    <input type="text" class="text" value="" placeholder="Entrez un titre">
                    <label for="mdp" class="label-primary">Description</label>
                    <textarea type="textarea" class="text" value="" placeholder="Entrez une description"></textarea>
                    <button type="submit" class="second-button">Enregistrer</button>
                </div>
            </form>
        HTML; 
        return $html;
    }
}