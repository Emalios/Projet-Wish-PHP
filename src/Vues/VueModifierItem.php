<?php

namespace App\Vues; 

use App\Vues\Vue as Vue; 
use App\Helpers\Elements as Elements; 


class VueModifierItem extends Vue{

    private $item;

    public function __construct($item,$c, $rq){
        parent::__construct("", $c, $rq);	
        $this->item = $item;
    }

    public function linkCss() : array{
        $links = ['elements/formulaire.css', 'elements/inputs.css', 'elements/basic.css'];
        return $links;
    }

    public function createContent() : string{
        $item = $this->item ; 
        $link = $this->requete->getUri()->getPath(); 

        ($item->img == "") ? $suppression = "" : $suppression = '<a href="$link?supprimer=image"> Supprimer l\'image de l\'item </a>';

        $html = <<<HTML
            <h1> Modifier un item a la liste : </h1>
            <form action=$link method="POST" class="center-right-form">
                <div class="center-form-inside">
                    <label for="nom" class="label-primary">Nom</label>
                    <input type="text" class="text" placeholder="Entrez le nom de l'item" name="nom" value="$item->nom">
                    <label for="nom" class="label-primary">Prix</label>
                    <input type="number" class="text" placeholder="Entrez le prix de l'item" name="prix" value="$item->tarif">
                    <label for="desc" class="label-primary">Description</label>
                    <textarea type="textarea" class="text" placeholder="Entrez une description" name="description">$item->descr
                    </textarea>
                    <label for="url" class="label-primary">Url</label>
                    <input type="text" class="text" placeholder="Entrez un url pour le produit" name="url" value="$item->url">
                    <label for="urlImage" class="label-primary">Url image</label>
                    <input type="text" class="text" placeholder="Entrez un url pour le produit" name="urlImage" value="$item->img">
                    <button type="submit" class="second-button">Modifier item</button>
                </div>
            </form>
            $suppression
        HTML; 
        return $html;
    } 

    public function notAccessible() : string{
        return "<h1> Vous ne pouvez pas modifier cet item </h1>";
    }
}