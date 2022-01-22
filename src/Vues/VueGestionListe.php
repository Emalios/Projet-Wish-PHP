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

    private $messagePartage;

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
    public function __construct($list, $items, $messages, $estProprio, $itemMessages,$c, $rq, $messagePartage = ""){
        parent::__construct("", $c, $rq);	
        if(!$list==null) {
            $this->list = $list; 
            $this->items = $items; 
            $this->messages = $messages;
            $this->estProprietaire  = $estProprio;
            $this->itemMessages  = $itemMessages;
            $this->messagePartage = $messagePartage;
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
        $links = ['pages/liste.css', 'elements/formulaire.css', 'elements/inputs.css'];
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
            <br>
            $this->messagePartage
            <p> $etat </p>
            <p> Description de la liste : $liste->description </p>
            <p> Date d'expiration : $liste->expiration </p> 
            <h3> La liste des items de la liste </h2>
            $listeItems
            <br>
            $sendMessage        
            $mess
        HTML; 
        return $html;
    }

    private function getOwnerFunctions(){
        $token = $this->list->token;
        $c = $this->container; 
        $addPath = $c->router->pathFor("ajouter-item", ["token" => $token]);
        $modifyPath = $c->router->pathFor("modifier-liste", ["id" => $token]);
        $sharePath = $c->router->pathFor("liste", ["token" => $token]);
        return <<<HTML
            <div>
                <a href="$addPath" class="add">Ajouter un item</a>
                <a href="$modifyPath" class="add">Modifier liste</a>
                <a href="$sharePath?partage=participant" class="add">Partager liste</a>
            </div>
        HTML; 
    }
    
    private function getFormMessage($token) : string{
        $token = $this->list->token; 
        $path = $this->container->router->pathFor("liste", ['token' => $token]);
        $html = <<<HTML
            <div class="add-comments">
                <h2> Commentaires </h2>
                <form action="$path" method="POST" class="center-right-form">
                    <label for="titre" class="label-primary">Message</label>
                    <textarea name="message" rows="5" column="300" placeholder="Ajouter un commentaire"></textarea>
                    <button type="submit" class="second-button">Envoyer</button>
                </form>
            </div>
        HTML;
        return $html;
    }
    
    private function getItemDesc($item, $tokenListe){
        if($item == null) return "";

        // Pour l'item, on affiche s'il est reserve ou non 
        if($item["nomReserveur"] == null) 
            $etat = "Il n'est pas réservé"; 
        else
            $etat = "Il est réservé ";
            
        // On ajoute des informations si la liste est depasse 
        $today = date("Y-m-d");      
        if($this->list->expiration <= $today) {
            ($this->itemMessages == null || !isset($this->itemMessages["$item->id"])) ? $message = "" : $message = $this->itemMessages["$item->id"];
            $etat .= "par <strong> $item->nomReserveur </strong>  : " . $message;
        }
            
        if(!$this->estProprietaire) $etat = ""; 

        if(filter_var($item["img"], FILTER_VALIDATE_URL)) $src = $item["img"]; 
        else $src =  "/img/$item->img";
    
        ($item->cagnotte != null) ? $cagnotte = "Cagnotte pour l'item : $item->cagnotte" : $cagnotte = "";

        if($this->estProprietaire && $item->nomReserveur == null)
            $link = $this->container->router->pathFor("modifier-item", ["token" => $this->list->token, "id" => $item->id]);
        else 
            $link = $this->container->router->pathFor("item",  ["token" => $this->list->token, "id" => $item->id]);

        ($item->url != "") ? $url = "Consulté le produit : <a href='$item->url' target='_blank'> $item->url </a>" : $url = ""; 
        return <<<HTML
            <p> <a href="$link"> $item->nom </a> 
            <p> $item->descr </p>
            <p> Cout = $item->tarif € <p>
            <p> $etat </p>
            <p> $url </p>
            <p> $cagnotte </p>
            <img src="$src" . alt="Image de l'item"/>
        HTML;
    }

    public function notFound() : string {
        return "<h1> Liste non trouvée </h1>";
    }
}