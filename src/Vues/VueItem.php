<?php 

namespace App\Vues;

use App\Model\Liste as Liste; 
use App\Model\Item as Item; 
use App\Model\Compte as Compte; 
use App\Vues\Vue; 
use Style\loadCss;


class VueItem extends Vue{


    private $item, $message; 

    public function __construct($item, $message, $c, $rq){
        parent::__construct("", $c, $rq); 
        $this->item = $item; 
        $this->message = $message; 
    } 

    public function linkCss() : array{
        $links = ['pages/item.css', 'elements/formulaire.css', 'pages/envoyer.css'];
        return $links;
    }

    public function createContent() : string{ 
        // On recupere l'item
        $item = $this->item;

        // S'il n'est pas associe a une liste 
        if($item["liste_id"] == 0) 
            return $this->notFound(); 

        // La racine du projet
        $rootFile = $this->requete->getUri()->getBasePath();

        // On affiche le reseveur et son message s'ils existent, un formulaire sinon
        ($this->message != null) ? $message = $this->message->commentaire : $message = ""; 
        ($item["nomReserveur"] == null) ? $form = $this->createForm() : $form = "Réservé par $item->nomReserveur"; 

        $html = <<<HTML
            <h1> $item->nom </h1>
            <h3> $item->descr </h3>
            <a href="//$item->url"> $item->url </a>
            <p> Tarif : $item->tarif €
            <br>
            <img src='$rootFile/img/$item->img' alt="Image">
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
        // On recupere l'item
        $item = $this->item;
        if(intval($item->cagnotte) >= $item->tarif) return "Item reservé par cagnotte";

        // Le lien de la page pour le formulaire
        $link = $this->requete->getUri()->getPath();

        // On s'occupe d'afficher une cagnotte si l'item en possede une 
        $formCagnotte = ""; 
        if($item->cagnotte != "" && Compte::isConnected()){
            $max = $item->tarif - $item->cagnotte;
            $middle =  $max /2;
            $basePath = $this->requete->getUri()->getBasePath(); 
            $formCagnotte = <<<HTML
                <div class="message">
                    <h1>Donner pour remplir la cagnotte</h1>
                    <h2>Cagnotte actuelle : $item->cagnotte €</h2>
                    <form action=$link method="post">
                        <div class="donation">
                            <input type="range" min="1" max="$max" value="$middle" class="slider" id="myRange" name="valeurCagnotte">
                            <p id="donation"></p>
                            <button type="submit" class="second-button">Participer</button>
                        </div>
                    </form> 
                </div>
                <script type="text/javascript" src="$basePath/css/pages/cagnotte.js"> 
                </script>
            HTML; 
        }

        (!isset($_COOKIE["username"])) ? $value = "" : $value = $_COOKIE["username"]; 
        $value = $_SESSION['login'] ?? $value; 

        $html = <<<HTML
            <div class="message">
                <form action="$link" method="POST" class="center-right-form">
                    <div class="send-message">
                        <h1> Reserver l'item </h1>
                        <label for="titre" class="label-primary">Nom</label>
                        <input type="text" class="text" value="$value" placeholder="Entrez un titre" name="nomReserveur">
                        <label for="titre" class="label-primary">Message</label>
                        <textarea class="text" name="message"></textarea>
                        <button type="submit" class="second-button">Enregistrer</button>
                    </div>
                </form>
            </div>
            $formCagnotte
        HTML; 
        return $html;
    }
}