<?php


namespace App\Controleurs; 

use App\Model\Compte;
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

    /**
     * Méthode permettant de récupèrer une liste à partir de son id ou bien du token de participation
     * @param Request $req
     * @param Response $resp
     * @param $args
     * @return erreur ou rien
     */
    public function getList(Request $req, Response $resp, $args){
        // On recupere la liste avec le token du proprietaire 
        $liste = Liste::where( 'token', '=', $args["token"] )->first();

        $esProprio = Liste::isOwner($liste); 

        // On recupere alors la liste des items associes 
        $listeItems = Item::where( 'liste_id', '=', $liste["no"])->get();
        
        // Ainsi que les messages 
        $messages = ListeMessage::where( 'liste_id', '=', $liste["no"])->get();
        
        // On regarde si un message a ete poste 
        if(isset($_POST['message'])){
            $rm = new ListeMessage();
            $rm->message = $_POST['message'];
            $rm->liste_id = $liste["no"];
            $rm->publieur_id = $_SESSION["userId"];
            $rm->save(); 
            header("Location: /liste/" . $args["token"]);
            exit; 
        }

        // On regarde s'il l'on a voulu partage la liste
        if($req->getQueryParams()["partage"] != null){
            $link = explode("?", $req->getUri())[0]; 
            $messagePartage = "Pour partager la liste, il vous suffit de partager le lien suivant : <a href=\"$link\">$link</a>";
        }
        
        $messagesItems = []; 
        foreach($listeItems as $item){
            $itemMessage = ReservationMessage::where( 'item_id', '=', $item["id"])->first();
            if($itemMessage != null)
            $messagesItems["$item->id"] =  $itemMessage->commentaire;
        }

        // Rendu 
        $vue = new Vues\VueGestionListe($liste, $listeItems, $messages, $estProprio, $messagesItems, $this->container, $req, $messagePartage);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

    /**
     * Méthode permettant de créer une liste à partir d'informations. Il faut être connecté
     * @param Request $req
     * @param Response $resp
     * @param $args
     * @return erreur ou rien
     */
    public function ajouterListe(Request $req, Response $resp, $args){
        //Si l'utilisateur n'est pas connecté, alors il ne peut pas créer de liste.
        if(isset($_POST['titre'])){
            $l = new Liste();
            $l->titre = $_POST['titre'];
            $l->description = $_POST['desc'];
            $l->token = bin2hex(openssl_random_pseudo_bytes(16)); 
            $l->tokenModification = bin2hex(openssl_random_pseudo_bytes(16)); 
            $l->publique = isset($_POST['publique']);
            if(isset($_SESSION['login'])){
                $c = Compte::where('login', '=', $_SESSION['login'])->first();
                $l->createur_id = $c->id;
            } else {
                setcookie($l->token, $l->tokenModification, 2147483647, "/", "", true); 
                $l->createur_id = 0; 
            }
            $annee = $_POST['année']; 
            $mois = $_POST['mois']; 
            $jour = $_POST['jour'];	
            $l->expiration = $annee . "-" .  $mois . "-" . $jour;
            $l->save();
            header("location: /ajouter-liste");
            exit;
        }   
        $vue = new Vues\VueAjoutListe($this->container, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

    /**
     * Méthode permettant de modifier une liste en fonction de son id
     * @param Request $req
     * @param Response $resp
     * @param $args
     * @return erreur ou rien
     */
    public function modifierListe(Request $req, Response $resp, $args){
        // Si l'utilisateur n'est pas connecté, alors il ne peut pas modifier de liste.
        $l = Liste::where( 'token', '=', $args["id"] )->first();

        // On vérifie que l'utilisateur soit le proprietaire de la liste 
        if(!Liste::isOwner($l)) {
            $redirection = $this->container->router->pathFor('liste', ["token" => $l->token]); 
            header("Location: $redirection");
            exit; 
        }

        // On recupere la liste des items 
        $listeItems = Item::where( 'liste_id', '=', $l["no"])->get();

        // Si l'utilisateur a voulu supprimer au moins un item
        if($req->getQueryParams()["supprimer"] != null){
            // On regarde s'il a voulu tous les supprimer
            if($_GET['supprimer'] == 'all'){
                $items = Item::where( 'liste_id', '=', $l->no)->get();
                foreach ($items as $item) {
                    $item->img = '';
                    $item->save();
                }
            } else {
                $item = Item::where( 'id', '=', $_GET['supprimer'])->first();
                if($item->nomReserveur == null)
                    $item->delete();
            }
            $redirection = $this->container->router->pathFor('modifier-liste', ["id" => $l->token]); 
            header("location: /modifier-liste/" . $l->token);
            exit; 
        }

        // Si l'utilisateur a essaye de modifier la liste
        if($req->getParsedBody() != null){
            $l->titre = $req->getParsedBody()['titre'];
            $l->description = $req->getParsedBody()['desc'];

            // On active ou non la liste publique
            if($_POST["publique"] == "on") $l->publique = 1;
            else $l->publique = 0;

            // On change eventuellement la date
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

    /**
     * méthode permettant d'afficher toutes les listes publiques
     * @param Request $req
     * @param Response $resp
     * @param $args
     * @return listes publiques
     */
    public function afficherListesPubliques(Request $req, Response $resp, $args){
        $listes = Liste::where("publique", "=", 1)->where("expiration", ">=", date("YYYY-mm-dd"))->get()->sortBy("expiration"); 
        $vue = new Vues\VueListesPubliques($listes,$this->container, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

    /**
     * méthode permettant de tester si les images existent bien
     * @return bool
     */
    public static function checkImg() {
        if((empty($_FILES['photo']['name']) || !exif_imagetype($_FILES['photo']['tmp_name']))) return false;
        return true;
    }

    /**
     * méthode permettant de bien formatter un lien vers les fichiers
     * @param string $fileName
     * @param string $subDirectory
     * @return string
     */
    public static function getValidLink(string $fileName, string $subDirectory = "") : string {
        if(strlen($fileName) > 30)  
            $fileName = substr($fileName, 0,20);
        $link =  $fileName;
        return $link;
    }
}