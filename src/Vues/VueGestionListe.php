<?php 

namespace App\Vues; 

use App\Model\Liste as Liste; 
use App\Model\Item as Item; 

use Style\loadCss;


class VueGestionListe extends Vue{


    private $list; 

    private $items; 

    private $estProprietaire; 

    private $messages;
     
    private $estReserve; 

    private $itemMessages;

    /**
     * constructeur par défault
     * @param $list liste à gérer
     * @param $items items de la liste
     * @param $messages messages de la liste
     * @param $estProprio booléen indiquant si la vue est affiché à quelqu'un étant le propriétaire de la liste
     * @param $itemMessages
     * @param $c
     * @param $rq
     */
    public function __construct($list, $items, $messages, $estProprio, $itemMessages,$c, $rq){
        parent::__construct("", $c, $rq);	
        if(!$list==null) {
            $this->list = $list; 
            $this->items = $items; 
            $this->messages = $messages;
            $this->estProprietaire  = $estProprio;
            $reserve = true; 
            foreach($this->items as $item){
                if($item["nomReserveur"] == null){
                    $reserve = false; 
                }
            }
            $this->estReserve = $reserve; 
        }
    } 

    public function linkCss() : string{
        $links = ['pages/liste.css'];
        return loadCss::toHtml($links);
    }

    public function createContent() : string{ 
        // Si la liste n'existe pas on affiche un resultat par defaut
        if($this->list == null) return $this->notFound(); 

        // Sinon on recupere les informations importantes 
        $titre = $this->list["titre"];
        $desc = $this->list["description"];
        $date = $this->list["expiration"];
        $listeItems = "";

        // On prepare l'affichage de chaque item
        foreach($this->items as $item){ 
            if($item["nomReserveur"] == null) $res = "Il n'est pas réservé";
            else $res = "Il est réservé par " . $item["nomReserveur"] . " : " . $this->itemMessages[$item->id]["message"];
            if(!$this->estProprietaire)                
                $res = ""; 
            $id = "'/item/" . $item["id"] . "'"; 
            $titreItem = $item["nom"];
            $desc = $item["descr"]; 
            $tarif = $item["tarif"];
            if(filter_var($item["img"], FILTER_VALIDATE_URL)) $src = $item["img"]; 
            else $src =  "'/img/" . $item["img"] . "'";

            if($item->cagnotte != "") $cagnotte = "Cagnotte pour l'item : " . $item["cagnotte"];
            else $cagnotte = "";

            $listeItems .= <<<HTML
                    <p> <a href=$id>  $titreItem </a> $desc . Cout = $tarif <p>
                    <p> $res </p>
                    <p> $cagnotte </p>
                    <img src=$src . alt="Image de l'item"/>
            HTML;
        }

        $mess = ""; 

        // On gere l'affichage des eventuels message 
        foreach ($this->messages as $message){
            $mess .= "<p> " . $message->message  . "</p><br/>";
        }

        $link = '"/liste/' . $this->list["token"] . '"';    
        if($this->estReserve) 
            $etat = "Cette liste est entièrement réservée";
        else 
            $etat = "Cette liste n'est pas entièrement réservée";
        $html = <<<HTML
            <h1> Liste : $titre </h1>
            <p> $etat </p>
            <p> $desc </p>
            <p> Date d'expiration : $date </p> 
            $listeItems
            $mess
            <form action=$link method="POST" class="center-right-form">
                <label for="titre" class="label-primary">Message</label>
                <textarea class="text" name="message"></textarea>
                <button type="submit" class="second-button">Envoyer</button>
            </form>
        HTML; 
        return $html;
    }

    public function notFound() : string {
        return "<h1> Liste non trouvée </h1>";
    }
}