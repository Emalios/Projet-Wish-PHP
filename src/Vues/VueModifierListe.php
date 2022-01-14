<?php

namespace App\Vues; 

use App\Vues\Vue as Vue; 
use Style\loadCss;
use App\Helpers\Elements as Elements; 


class VueModifierListe extends Vue{


    private $liste; 

    private $items;

    public function __construct($liste, $items, $c, $rq){
        parent::__construct("", $c, $rq);	
        $this->liste = $liste;
        $this->items = $items;
    }

    public function linkCss() : string{
        $links = ['elements/formulaire.css', 'elements/inputs.css', 'elements/basic.css'];
        return loadCss::toHtml($links);
    }

    public function createContent() : string{
        $cookieName = "propListe" . $this->liste["no"]; 
        if(!isset($_COOKIE[$cookieName]) || $_COOKIE[$cookieName] != $this->liste["token"]) return $this->notAccessible(); 
        $titre = $this->liste["titre"]; 
        $description = $this->liste["description"];
        $date = Elements::dateDropDown($this->liste['expiration']); 
        $link = "/modifier-liste/" . $this->liste['token']; 

        $items = ""; 
        $token = $this->liste["token"];
        foreach($this->items as $item){
            if($item["nomReserveur"] == null){
                $nom = $item['nom'];   
                $id = $item['id']; 
                $items .= "<a href='/modifier-item/$id'>$nom </a><a href='/modifier-liste/$token?supprimer=$id'>supprimer</a><br>";
            }
        }

        $deleteAllImages = "<a href='/modifier-liste/$token?supprimer=all'>supprimer toutes les images</a><br>";

        echo($this->liste->publique);
        $valuePublique = ($this->liste->publique == 1) ? "checked" : ""; 
        echo($valuePublique);
        $html = <<<HTML
            <h1> Modifier une liste : </h1>
            <form action=$link method="POST" class="center-right-form">
                <div class="center-form-inside">
                    <label for="titre" class="label-primary">Titre</label>
                    <input type="text" class="text" value="$titre" placeholder="Entrez un titre" name="titre">
                    <label for="mdp" class="label-primary">Liste publique</label>
                    <input type="checkbox" name="publique" $valuePublique>
                    <label for="desc" class="label-primary">Description</label>
                    <textarea type="textarea" class="text" placeholder="Entrez une description" name="desc">$description
                    </textarea>
                    $date
                    <button type="submit" class="second-button">Modifier</button>
                </div>
            </form>
            <h1> Ajouter un item a la liste : </h1>
            <form action=$link method="POST" class="center-right-form" enctype="multipart/form-data">
                <div class="center-form-inside">
                    <label for="nom" class="label-primary">Nom</label>
                    <input type="text" class="text" placeholder="Entrez le nom de l'item" name="nom">
                    <label for="nom" class="label-primary">Prix</label>
                    <input type="number" class="text" placeholder="Entrez le prix de l'item" name="prix">
                    <label for="desc" class="label-primary">Description</label>
                    <textarea type="textarea" class="text" placeholder="Entrez une description" name="description">
                    </textarea>
                    <label for="cagnotte" class="label-primary">Souhaitez vous créer une cagnotte ? </label>
                    <input type="checkbox" name="cagnotte">
                    <label for="desc" class="label-primary">Url</label>
                    <input type="text" class="text" placeholder="Entrez un url pour le produit" name="url">
                    <label for="urlImage" class="label-primary">Url image</label>
                    <input type="text" class="text" placeholder="Entrez un url pour le produit" name="urlImage">
                    <label for="urlImage" class="label-primary">Url image</label>
                    <div class="center-third-input">
                        <p class="category">Ajoutez votre propre image: </p>
                        <input type="file" name="photo" class="imgExpo">
                    </div>
                    
                    <button type="submit" class="second-button">Ajouter item</button>
                </div>
            </form>
            $items
            $deleteAllImages
        HTML; 
        return $html;
    } 

    public function notAccessible() : string{
        return "<h1> Vous ne pouvez pas modifier cette liste </h1>";
    }
}