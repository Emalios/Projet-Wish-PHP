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


    public function getItem(Request $req, Response $resp, $args){
        $liste = Item::where( 'id', '=', $args["id"] )->first();
        $vue = new Vues\VueGestionListe($liste, $listeItems, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

}