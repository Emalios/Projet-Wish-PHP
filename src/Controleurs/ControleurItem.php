<?php


namespace App\Controleurs; 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\Liste as Liste; 
use App\Model\ListeCompte as ListeCompte; 
use App\Model\ReservationMessage as ReservationMessage; 
use App\Model\Item as Item; 
use App\Vues as Vues; 


class ControleurItem {

    private $container;

    public function __construct($c) {
        $this->container = $c;
    }


    /**
     * méthode retournant un item s'il existe
     * @param Request $req
     * @param Response $resp
     * @param $args
     * @return Item s'il existe sinon rien
     */
    public function getItem(Request $req, Response $resp, $args){
        $item = Item::where( 'id', '=', $args["id"] )->first();
        if(isset($_POST['nomReserveur'])){
            $item["nomReserveur"] = $req->getParsedBody()["nomReserveur"];
            $item->save();
            if(isset($_POST['message'])){
                $m = new ReservationMessage();
                $m->commentaire = $_POST['message'];
                $m->item_id = $args["id"];
                $m->save(); 
            }
            
            if(!isset($_COOKIE["username"])){
                setcookie("username", $item["nomReserveur"], -1);
            }
            header("Location:/item/" . $args["id"]);
            exit;
        } else if(isset($_POST["valeurCagnotte"])){
            $item->cagnotte = $_POST["valeurCagnotte"] + $item->cagnotte;
            $item->save(); 
            if(isset($_SESSION['login'])){
                $listeCompte = new ListeCompte(); 
                $listeCompte->idListe = $item->liste_id; 
                $listeCompte->loginCompte = "aa";
                $listeCompte->participation = $item->cagnotte;
                $listeCompte->save(); 
            }
            header("Location:/item/" . $args["id"]);
            exit;
        }
        $message = ReservationMessage::where( 'item_id', '=', $args["id"] )->first();
        $vue = new Vues\VueItem($item, $message, $this->container, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

    /**
     * Méthode permettant de changer les charactèristiques d'un item
     * @param Request $req
     * @param Response $resp
     * @param $args
     * @return erreur ou rien
     */
    public function modifierItem(Request $req, Response $resp, $args){
        $item = Item::where( 'id', '=', $args["id"] )->first();
        $l = Liste::where( 'no', '=', $item["liste_id"] )->first();
        if(isset($_POST['nom'])){
            $item->nom = $_POST['nom'];
            $item->descr = $_POST['description']; 
            $item->tarif = $_POST['prix'];	  
            $item->url = $_POST['url'];
            $item->img = $_POST['urlImage'];
            $item->save(); 
            header("location: /modifier-liste/" . $l->token);
            exit;
        }
        $vue = new Vues\VueModifierItem($item, $this->container, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

}