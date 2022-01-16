<?php 

namespace App\Vues; 

use App\Model\Liste as Liste; 
use App\Model\Item as Item; 
use App\Model\Compte as Compte; 

use Style\loadCss;


class VueGestionListe extends Vue{


    private $list; 

    private $items; 

    private $estProprietaire; 

    private $messages;
     
    private $estReserve; 

    private $itemMessages; 

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

    private function getFormMessage() : string{
        $html = <<<HTML
        HTML;
        return $html;
    }

    private function getItemDesc($item, $tokenListe){
        if($item == null) return "";
        if($item["nomReserveur"] == null) 
            $etat = "Il n'est pas réservé"; 
        else{
            ($this->itemMessages == null || $this->itemMessages[$item->id]["message"] == null) ? $message = "" : $message = $this->itemMessages[$item->id]["message"];
            $etat = "Il est réservé par $item->nomReserveur  : " . $message;
        }
        if(!$this->estProprietaire) $res = ""; 
        if(filter_var($item["img"], FILTER_VALIDATE_URL)) $src = $item["img"]; 
        else $src =  "/img/$item->img";

        ($item->cagnotte != "") ? $cagnotte = "Cagnotte pour l'item : $item->cagnotte" : $cagnotte = "";

        return <<<HTML
            <p> <a href="/$tokenListe/item/$item->id">  $item->titreItem </a> $item->desc Cout = $item->tarif <p>
            <p> $etat </p>
            <p> $cagnotte </p>
            <img src="$src" . alt="Image de l'item"/>
        HTML;
    }

    public function linkCss() : string{
        $links = ['pages/liste.css'];
        return loadCss::toHtml($links);
    }
    public function createContent() : string{ 
        // Si la liste n'existe pas on affiche un resultat par defaut
        if($this->list == null) return $this->notFound(); 

        $liste = $this->list;
        $token = $liste->token;

        $listeItems = "";

        // On prepare l'affichage de chaque item
        foreach($this->items as $item){ 
            $listeItems .= $this->getItemDesc($item, $token);
        }

        $mess = ""; 

        // On gere l'affichage des eventuels message 
        foreach ($this->messages as $message){
            $mess .= "<p> " . $message->message  . "</p><br/>";
        }

        if($this->estReserve) 
            $etat = "Cette liste est entièrement réservée";
        else 
            $etat = "Cette liste n'est pas entièrement réservée";

        if(Compte::isConnected())
            $sendMessage = $this->getFormMessage(); 
        else 
            $sendMessage = "Pour envoyer un message, il faut se connecter";

        $html = <<<HTML
            <h1> Liste : $liste->titre </h1>
            <p> $etat </p>
            <p> $liste->description </p>
            <p> Date d'expiration : $liste->expiration </p> 
            $listeItems
            $mess
            $sendMessage        
        HTML; 
        return $html;
    }

    public function notFound() : string {
        return "<h1> Liste non trouvée </h1>";
    }
}