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

    
    public function linkCss() : array{
        $links = ['pages/liste.css', 'pages/formulaire.css'];
        return $links;
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
            $etat = "Etat : cette liste est entièrement réservée";
        else 
            $etat = "Etat : cette liste n'est pas entièrement réservée";
        
        if(!$this->estProprietaire)
            $etat = ""; 
        if(Compte::isConnected())
            $sendMessage = $this->getFormMessage($token); 
        else 
            $sendMessage = "Pour envoyer un message, il faut se connecter";
        
        if($this->estProprietaire)
            $ownerFunctions = $this->getOwnerFunctions(); 
        else 
            $ownerFunctions = ""; 
        
        $html = <<<HTML
            <h1 class="center-title"> Liste : $liste->titre </h1>
            $ownerFunctions
            <p> $etat </p>
            <p> Description de la liste : $liste->description </p>
            <p> Date d'expiration : $liste->expiration </p> 
            <h3> La liste des items de la liste </h2>
            $listeItems
            $mess
            $sendMessage        
        HTML; 
        return $html;
    }

    private function getOwnerFunctions(){
        $token = $this->list->token;
        return <<<HTML
            <div>
                <a href="" class="add">Ajouter un item</a>
                <a href="/modifier-liste/$token" class="add">Modifier liste</a>
                <a href="" class="add">Supprimer liste</a>
            </div>
        HTML; 
    }
    
    private function getFormMessage($token) : string{
        $link = "'/liste/$token'"; 
        $html = <<<HTML
            <form action=$link method="POST" class="center-right-form">
                <label for="titre" class="label-primary">Message</label>
                <textarea class="text" name="message"></textarea>
                <button type="submit" class="second-button">Enregistrer</button>
            </form>
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
        $today = date("Y-m-d");      
        if(!$this->estProprietaire || $this->list->expiration > $today) $etat = ""; 
        if(filter_var($item["img"], FILTER_VALIDATE_URL)) $src = $item["img"]; 
        else $src =  "/img/$item->img";
    
        ($item->cagnotte != "") ? $cagnotte = "Cagnotte pour l'item : $item->cagnotte" : $cagnotte = "";
    
        return <<<HTML
            <p> <a href="/liste/$tokenListe/item/$item->id"> $item->nom </a> 
            <p> $item->descr </p>
            <p> Cout = $item->tarif € <p>
            <p> $etat </p>
            <p> $cagnotte </p>
            <img src="$src" . alt="Image de l'item"/>
        HTML;
    }

    public function notFound() : string {
        return "<h1> Liste non trouvée </h1>";
    }
}