<?php 

namespace App\Vues; 

use App\Vues\Vue as Vue;
use Style\loadCss;
use App\Helpers\Elements as Elements; 

class VueAjoutItem extends Vue{

    private $token; 

    public function __construct( $c, $rq, $tokenListe){
        parent::__construct("", $c, $rq);
        $this->token = $tokenListe; 
    }

    public function linkCss(): array{
        $links = ['elements/formulaire.css', 'elements/inputs.css', 'elements/basic.css'];
        return $links;
    }

    public function notAccessible(){
        return "<h1> Vous n'avez pas accès à cette liste </h1>"; 
    }

    public function createContent() : string{ 
        $date = Elements::dateDropDown(); 
        $ajoutItem = $this->container->router->pathFor('ajouter-item', ["token" => $this->token]); 
        $listeLink = $this->container->router->pathFor('liste', ["token" => $this->token]); 
        $html = <<<HTML
            <h1> Ajouter un item a la liste : </h1>
            <form action="$ajoutItem" method="POST" class="center-right-form" enctype="multipart/form-data">
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
                    <div class="center-third-input">
                        <p class="category">Ajoutez votre propre image: </p>
                        <input type="file" name="photo" class="imgExpo">
                    </div>
                    
                    <button type="submit" class="second-button">Ajouter item</button>
                </div>
            </form>
            <a href="$listeLink"> Retourner à la liste </a>
        HTML; 
        return $html;
    }
}