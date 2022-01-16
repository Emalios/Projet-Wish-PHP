<?php

namespace App\Vues; 

use App\Vues\Vue as Vue; 
use Style\loadCss;
use App\Helpers\Elements as Elements; 


class VueModifierItem extends Vue{

    private $item;

    public function __construct($item,$c, $rq){
        parent::__construct("", $c, $rq);	
        $this->item = $item;
    }

    public function linkCss() : string{
        $links = ['elements/formulaire.css', 'elements/inputs.css', 'elements/basic.css'];
        return loadCss::toHtml($links);
    }

    public function createContent() : string{
     //   $cookieName = "propListe" . $this->item["liste_id"]; 
       // if(!isset($_COOKIE[$cookieName])) return $this->notAccessible(); 
        $nom = $this->item["nom"] ; 
        $prix = '"' . $this->item["tarif"] . '"'; 
        $url = '"' . $this->item["url"] . '"'; 
        $urlImg = '"' . $this->item["img"] . '"'; 
        $description = $this->item["descr"];
        $link = "/modifier-item/" . $this->item['id']; 

        $html = <<<HTML
            <h1> Modifier un item a la liste : </h1>
            <form action=$link method="POST" class="center-right-form">
                <div class="center-form-inside">
                    <label for="nom" class="label-primary">Nom</label>
                    <input type="text" class="text" placeholder="Entrez le nom de l'item" name="nom" value="{$nom}">
                    <label for="nom" class="label-primary">Prix</label>
                    <input type="number" class="text" placeholder="Entrez le prix de l'item" name="prix" value=$prix>
                    <label for="desc" class="label-primary">Description</label>
                    <textarea type="textarea" class="text" placeholder="Entrez une description" name="description">$description
                    </textarea>
                    <label for="url" class="label-primary">Url</label>
                    <input type="text" class="text" placeholder="Entrez un url pour le produit" name="url" value=$url>
                    <label for="urlImage" class="label-primary">Url image</label>
                    <input type="text" class="text" placeholder="Entrez un url pour le produit" name="urlImage" value=$urlImg>
                    <button type="submit" class="second-button">Modifier item</button>
                </div>
            </form>
        HTML; 
        return $html;
    } 

    public function notAccessible() : string{
        return "<h1> Vous ne pouvez pas modifier cet item </h1>";
    }
}