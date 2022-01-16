<?php 

namespace App\Vues;

use App\Model\Liste as Liste; 
use App\Model\Item as Item; 
use App\Vues\Vue; 
use Style\loadCss;


class VueItem extends Vue{


    private $item, $message; 

    public function __construct($item, $message, $c, $rq){
        parent::__construct("", $c, $rq); 
        $this->item = $item; 
        $this->message = $message; 
    } 

    public function linkCss() : string{
        $links = ['pages/liste.css'];
        return loadCss::toHtml($links);
    }

    public function createContent() : string{ 
        if($this->item["liste_id"] == 0) return $this->notFound(); 
        $titre = $this->item["nom"];
        $src = "'/img/" . $this->item["img"] . "'";
        ($this->message != null) ? $message = $this->message->commentaire : $message = ""; 
        ($this->item["nomReserveur"] == null) ? $form = $this->createForm() : $form = "Réservé par " . $this->item["nomReserveur"]; 
        $html = <<<HTML
            <h1> $titre </h1>
            <img src=$src alt="Image">
            $form
            <br />
            $message
        HTML;
        return $html;
    }

    public function notFound() : string {
        return "<h1> Item non trouvé </h1>";
    }

    /**
     * méthode permettant de créer un formulaire afin de créer un item
     * @return string
     */
    public function createForm() : string{
        $link = '"/item/' . $this->item["id"] . '"';
        $formCagnotte = ""; 
        if($this->item->cagnotte != ""){
            $tarif = $this->item->tarif; 
            $cagnotte = $this->item->cagnotte;
            $max = '"' . $tarif - $cagnotte . '"';
            $middle =  '"' . (($tarif - $cagnotte)/2) . '"';
            $formCagnotte = <<<HTML
                <form action=$link method="post">
                    <label for="titre" class="label-primary">Donner pour remplir la cagnotte</label>
                    <p>Cagnotte actuelle : $cagnotte</p>
                    <p>1</p>
                    <input type="range" min="1" max=$max value=$middle class="slider" id="myRange" name="valeurCagnotte">
                    <p> $max </p>
                    <button type="submit">Enregistrer</button>
                </form> 
            HTML; 
        }
        (!isset($_COOKIE["username"])) ? $value = "\"\"" : $value = '"' . $_COOKIE["username"] . '"'; 
        $html = <<<HTML
        <form action=$link method="POST" class="center-right-form">
            <label for="titre" class="label-primary">Nom</label>
            <input type="text" class="text" value=$value placeholder="Entrez un titre" name="nomReserveur">
            <label for="titre" class="label-primary">Message</label>
            <textarea class="text" name="message"></textarea>
            <button type="submit" class="second-button">Enregistrer</button>
        </form>
        $formCagnotte
        <script type="text/javascript">
            var slider = document.getElementById("myRange");
            var output = document.getElementById("demo");
            output.innerHTML = slider.value; // Display the default slider value

            // Update the current slider value (each time you drag the slider handle)
            slider.oninput = function() {
                output.innerHTML = this.value + "€";
            } 
        </script>
    HTML; 
        return $html;
    }
}