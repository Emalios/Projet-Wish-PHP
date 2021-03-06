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

    public function linkCss() : array{
        $links = ['elements/formulaire.css', 'elements/inputs.css', 'elements/basic.css'];
        return $links;
    }

    public function createContent() : string{
      //  $cookieName = "propListe" . $this->liste["no"]; 
        //if(!isset($_COOKIE[$cookieName]) || $_COOKIE[$cookieName] != $this->liste["token"]) return $this->notAccessible(); 
        $titre = $this->liste["titre"]; 
        $description = $this->liste["description"];
        $date = Elements::dateDropDown($this->liste['expiration']); 
        $link = $this->container->router->pathFor('modifier-liste', ["token"=> $this->liste['token']); 
        $items = "";
        $token = $this->liste["token"];
        foreach($this->items as $item){
            if($item["nomReserveur"] == null){
                $nom = $item['nom'];   
                $id = $item['id']; 
                $pathModifierItem = $this->container->router->pathFor('modifier-item', ["token"=> $this->liste['token'], "id" => $id); 
                $pathSupprimerItem = $this->container->router->pathFor('modifier-liste', ["token"=> $this->liste['token']); 
                $items .= "<a href='$pathModifierItem'>$nom </a><a href='$pathSupprimerItem?supprimer=$id'>supprimer</a><br>";
            }
        }

        $pathSupprimerImages = $this->container->router->pathFor('modifier-liste', ["token"=> $this->liste['token']); 
        $deleteAllImages = "<a href='$pathSupprimerImages?supprimer=all'>supprimer toutes les images</a><br>";

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
            $items
            $deleteAllImages
        HTML; 
        return $html;
    } 

    /*public function notAccessible() : string{
        return "<h1> Vous ne pouvez pas modifier cette liste </h1>";
    }*/
}