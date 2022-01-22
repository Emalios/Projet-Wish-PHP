<?php


namespace App\Controleurs; 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\Liste as Liste; 
use App\Model\ListeCompte as ListeCompte; 
use App\Model\Compte as Compte; 
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
        // On recupere l'item 
        $item = Item::where( 'id', '=', $args["id"] )->first();
        $l = Liste::where( 'token', '=', $args["token"] )->first();

        // Si l'utilisateur possede la liste de l'item alors il est redirige vers la page de modification de l'item
        if(Liste::isOwner($l) && $item->nomReserveur == null) {
            $redirection = $this->container->router->pathFor('modifier-item', ["id" => $l->token]); 
            header("Location: $redirection");
            exit; 
        }

        if($req->getParsedBody() == null){
            $message = ReservationMessage::where( 'item_id', '=', $args["id"] )->first();
            $vue = new Vues\VueItem($item, $message, $this->container, $req);
            $resp->getBody()->write($vue->render());
            return $resp;
        }

        if($req->getParsedBody()['nomReserveur'] != null){
            $item["nomReserveur"] = $req->getParsedBody()["nomReserveur"];
            $item->save();
            if($req->getParsedBody()['message']) != null){
                $m = new ReservationMessage();
                $m->commentaire = $req->getParsedBody()['message'];
                $m->item_id = $args["id"];
                $m->save(); 
            }
            
            if(!isset($_COOKIE["username"])) setcookie("username", $item["nomReserveur"], -1, "/");
            $path = $this->container->router->pathFor('item', ["token" => $args["token"], "id" => $args["id"]]);
            header("Location: $path");
            exit;
        } else if($req->getParsedBody()["valeurCagnotte"] != null){
            $item->cagnotte = $req->getParsedBody()["valeurCagnotte"] + $item->cagnotte;
            $item->save(); 
            if(Compte::isConnected()){
                $l = ListeCompte::getParticipation($item->liste_id);
                $participation = 0; 
                if($l != null){
                    $participation = intval($l->participation);
                    $l->delete();
                }
                $liste = new ListeCompte(); 
                $liste->idListe = $item->liste_id; 
                $liste->idCompte = $_SESSION["userId"];
                $liste->participation = $req->getParsedBody()["valeurCagnotte"] + $participation;
                $liste->save(); 
            }
            $path = $this->container->router->pathFor('item', ["token" => $args["token"], "id" => $args["id"]]);
            header("Location: $path");
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
        // On recupere l'item
        $item = Item::where( 'id', '=', $args["id"] )->first();

        // Si l'item est reserve, on redirige l'utilisateur vers la page de la liste 
        if($item->nomReserveur != null){
            header("location: " . $this->container->router->pathFor("liste", ["token" => $args["token"]]));
            exit;
        }

        if($req->getQueryParams()["supprimer"] != null){
            if($req->getQueryParams()["supprimer"] == "image"){
                $item->img = ""; 
                $item->save();
            }
        }

        if($req->getParsedBody() != null){
            $item->nom = $req->getParsedBody()['nom'];
            $item->descr = $req->getParsedBody()['description']; 
            $item->tarif = $req->getParsedBody()['prix'];	  
            $item->url = $req->getParsedBody()['url'];
            $item->img = $req->getParsedBody()['urlImage'];
            $item->save(); 
            header("location: " . $this->container->router->pathFor("liste", ["token" => $args["token"]]));
            exit;
        }
        $vue = new Vues\VueModifierItem($item, $this->container, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

    public function ajouterItem(Request $req, Response $resp, $args){
        // On recupere la liste associee
        $l = Liste::where( 'token', '=', $args["token"] )->first();

        // Si l'utilisateur a essaye de rentrer de rentrer un item
        if(!Liste::isOwner($l)) {
            $redirection = $this->container->router->pathFor('liste', ["token" => $l->token]); 
            header("Location: $redirection");
            exit; 
        }

        if($req->getParsedBody() != null){
            $item = new Item(); 
            $item->nom = $req->getParsedBody()["nom"];
            $item->descr = $req->getParsedBody()["description"];
            $item->liste_id = Liste::where("token", "=", $args['token'])->first()["no"];
            $item->url = $req->getParsedBody()["url"] ?? ""; 
            $item->tarif = $req->getParsedBody()["prix"];
            $item->img = $req->getParsedBody()["urlImage"];
            $item->save(); 
        }
        $vue = new Vues\VueAjoutItem($this->container, $req, $args["token"]);
        $resp->getBody()->write($vue->render());
        return $resp;
    }
}