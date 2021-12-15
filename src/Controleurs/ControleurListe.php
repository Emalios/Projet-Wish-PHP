<?php


namespace App\Controleurs; 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\Liste as Liste; 
use App\Model\Item as Item; 
use App\Vues as Vues; 


class ControleurListe {


    public function __construct() {
    }


    public function getList(Request $req, Response $resp, $args){
        $liste = Liste::where( 'no', '=', $args["id"] )->first();
        $listeItems = Item::where( 'liste_id', '=', $liste["no"])->get();
        $vue = new Vues\VueGestionListe($liste, $listeItems, $req->getUri()->getBasePath());
        $resp->getBody()->write($vue->render());
        return $resp;
    }

    public function ajouterListe(Request $req, Response $resp, $args){
        $vue = new Vues\VueAjoutListe();
        $resp->getBody()->write($vue->render());
        return $resp;
    }


}