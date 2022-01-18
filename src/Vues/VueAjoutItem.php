<?php 

namespace App\Vues; 

use App\Vues\Vue as Vue;
use Style\loadCss;
use App\Helpers\Elements as Elements; 

class VueAjoutItem extends Vue{

    private $items;

    public function __construct($items, $c, $rq){
        parent::__construct("", $c, $rq);
        $this->items = $items;
    }

    public function linkCss(): string{
        $links = ['elements/formulaire.css', 'elements/inputs.css', 'elements/basic.css'];
        return $links;
    }

    public function createContent() : string{ 
        $date = Elements::dateDropDown(); 
        $html = <<<HTML
            <h1> Ajouter un item a la liste : </h1>
            <form action="/ajouter-item" method="POST" class="center-right-form" enctype="multipart/form-data">
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
        HTML; 
        return $html;
    }
}