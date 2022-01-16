<?php


namespace App\Controleurs; 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\Liste as Liste; 
use App\Model\Item as Item; 
use App\Model\ListeMessage as ListeMessage; 
use App\Model\ReservationMessage as ReservationMessage; 
use App\Vues as Vues; 


class ControleurListe {

    private $container; 

    public function __construct($c) {
        $this->container = $c;
    }


    public function getList(Request $req, Response $resp, $args){
        // On recupere la liste avec le token du proprietaire 
        $liste = Liste::where( 'token', '=', $args["token"] )->first();

        // Si on ne l'a pas trouvé alors on essaie de le trouver avec le token de participation
        if($liste == null){
            $liste = Liste::where( 'tokenParticipation', '=', $args["id"] )->first();
            // Dans ce cas ce n'est pas le proprietaire de la liste 
            $estProprio = false; 
        } else {
            // Sinon on verifie que ce soit bien le proprietaire
            $cookieName = 'propListe' . $liste["no"];
            $estProprio = isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] == $liste["token"];
        }
        
        // On recupere alors la liste des items associes 
        $listeItems = Item::where( 'liste_id', '=', $liste["no"])->get();
        
        // Ainsi que les messages 
        $messages = ListeMessage::where( 'liste_id', '=', $liste["no"])->get();
        
        // On regarde si un message a ete poste 
        if(isset($_POST['message'])){
            $rm = new ListeMessage();
            $rm->message = $_POST['message'];
            $rm->liste_id = $liste["no"];
            $rm->save(); 
            header("Location: /liste/" . $args["id"]);
            exit; 
        }
        
        $messagesItems = []; 
        foreach($listeItems as $item){
            $itemMessage = ReservationMessage::where( 'item_id', '=', $item["id"])->first();
            if($itemMessage != null)
            $messagesItems[$item->id] =  $itemMessage->commentaire;
        }

        // Rendu 
        $vue = new Vues\VueGestionListe($liste, $listeItems, $messages, $estProprio, $messagesItems, $this->container, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

    public function ajouterListe(Request $req, Response $resp, $args){
        if(isset($_POST['titre'])){
            $l = new Liste();
            $l->titre = $_POST['titre'];
            $l->description = $_POST['desc'];
            $l->token = bin2hex(openssl_random_pseudo_bytes(16)); 
            $l->tokenParticipation = bin2hex(openssl_random_pseudo_bytes(16)); 
            $l->publique = ($_POST['publique'] == 'on') ? 1 : 0;
            $l->createur_login = (isset($_SESSION['login'])) ? $_SESSION['login'] : "Non identifié"; 
            $annee = $_POST['année']; 
            $mois = $_POST['mois']; 
            $jour = $_POST['jour'];	
            $l->expiration = $annee . "-" .  $mois . "-" . $jour;
            $l->save();
            setcookie("propListe" . $l->no, $l->token, -1); 
            header("location: /ajouter-liste");
            exit;
        }   
        $vue = new Vues\VueAjoutListe($this->container, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

    public function modifierListe(Request $req, Response $resp, $args){
        $l = Liste::where( 'token', '=', $args["id"] )->first();
        $listeItems = Item::where( 'liste_id', '=', $l["no"])->get();
        if($req->getQueryParam()["supprimer"]){
            if($_GET['supprimer'] == 'all'){
                $items = Item::where( 'liste_id', '=', $l->no)->get();
                foreach ($items as $item) {
                    $item->img = '';
                    $item->save();
                }
            } else {
                $item = Item::where( 'id', '=', $_GET['supprimer'])->first();
                $item->delete();
            }
            header("location: /modifier-liste/" . $l->token);
            exit; 
        }
        if(isset($_POST['nom'])){
            $maxId = Item::max('id'); 
            $item = new Item();
            $item->liste_id = $l->no; 
            $item->nom = $_POST['nom'];
            $item->descr = $_POST['description']; 
            $item->tarif = $_POST['prix'];	  
            $item->url = $_POST['url'];
            if(static::checkImg()) {
                $link = static::getValidLink($_FILES['photo']['name']);
                copy($_FILES['photo']['tmp_name'], "img/" . ($maxId+1) . $link);
                $item->img = ($maxId+1) . $link;
            }
            else $item->img = $_POST['urlImage'];
            if($_POST["cagnotte"] == "on") $item->cagnotte = 0;
            $item->save(); 
     //       header("location: /modifier-liste/" . $l->token);
       //     exit; 
        }
        else if(isset($_POST['titre'])){
            $l->titre = $_POST['titre'];
            $l->description = $_POST['desc'];
            if($_POST["publique"] == "on") $l->publique = 1;
            else $l->publique = 0;
            $annee = $_POST['année']; 
            $mois = $_POST['mois']; 
            $jour = $_POST['jour'];	
            $l->expiration = $annee . "-" .  $mois . "-" . $jour;
            $l->save();
            header("location: /liste/" . $l->token);
            exit; 
        }   
        $vue = new Vues\VueModifierListe($l, $listeItems, $this->container, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

    public function afficherListesPubliques(Request $req, Response $resp, $args){
        $listes = Liste::where("publique", "=", 1)->where("expiration", ">=", date("YYYY-mm-dd"))->get()->sortBy("expiration"); 
        $vue = new Vues\VueListesPubliques($listes,$this->container, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

    public static function checkImg() {
        if((empty($_FILES['photo']['name']) || !exif_imagetype($_FILES['photo']['tmp_name']))) return false;
        return true;
    }

    public static function getValidLink(string $fileName, string $subDirectory = "") : string {
        if(strlen($fileName) > 30)  
            $fileName = substr($fileName, 0,20);
        $link =  $fileName;
        return $link;
    }
}