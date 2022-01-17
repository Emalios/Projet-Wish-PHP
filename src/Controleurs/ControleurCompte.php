<?php

namespace App\Controleurs;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\Liste as Liste; 
use App\Model\Item as Item; 
use App\Model\ListeCompte as ListeCompte; 
use App\Model\Compte as Compte; 
use App\Vues as Vues; 

class ControleurCompte{

    private $container;

    public function __construct($c) {
        $this->container = $c;
    }



    /**
     * Methode permettant de creer un compte sur le site 
     * Cette méthode affiche une page d'inscription si l'utilisateur n'a rien rentre 
     * sinon elle redirige vers la page de connexion 
     */
    public function creerCompte(Request $req, Response $resp, $args){
        $messageErreur = ""; 
        // Si l'utilisateur a essaye de s'inscrire 
        if($req->getParsedBody() != null) {
            $res = Compte::createUser($req->getParsedBody()["email"], $req->getParsedBody()["login"], $req->getParsedBody()["mdp"]);
            if(is_string($res)) 
                $messageErreur = $res;
            else {
                header('location: /login');
                exit;
            }
        }
        $vue = new Vues\VueCreationCompte($messageErreur, $this->container, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

    /**
     * @param Request $req requête
     * @param Response $resp réponse
     * @param $args arguments
     * @return rien si déjà connecté, sinon erreur si mauvais identifiants
     */
    public function seConnecter(Request $req, Response $resp, $args){
        if(isset($_SESSION['login'])) {
            header('location: /accueil'); 
            exit;
        }
        if($req->getParsedBody() != null) {
            $email = $req->getParsedBody()['email']; 
            $password = $req->getParsedBody()['mdp']; 
            $res = Compte::seConnecter($email, $password); 
            if($res) {
                header('location: /accueil'); 
                exit;
            }  
        }
        $vue = new Vues\VueLogin($this->container, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

    /**
     * méthode redirigeant l'utilisateur en fonction de ce qu'il demande, en fonction du paramètre 'query'
     * @param Request $req
     * @param Response $resp
     * @param $args
     * @return Response|void
     */
    public function gestionCompte(Request $req, Response $resp, $args){
        if($req->getQueryParams()["query"] == "participations"){
            $participations = ListeCompte::where("loginCompte", "=", $_SESSION['login'])->get(); 
            $listes = new \ArrayObject(array(), \ArrayObject::STD_PROP_LIST);
            foreach($participations as $participation){
                $liste = Liste::where("no", "=", $participation->idListe)->first(); 
                $listes[] = $liste;
            }
            $vue = new Vues\VueCompteParticipation(Compte::where('login', '=', $_SESSION['login'] )->first(), $listes, $participations, $this->container, $req);
        }
        else if($req->getQueryParams()["query"] == "supprimer"){
            $c = Compte::where("login", "=", $_SESSION["login"])->delete();
            header("/accueil"); 
            exit; 
        }
        else if($req->getQueryParams()["query"] == "deconnexion"){
            session_destroy();
            header("/accueil"); 
            exit; 
        }
        else $vue = new Vues\VueCompte(Compte::where('login', '=', $_SESSION['login'] )->first(), $this->container, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

    /**
     * méthode permettant de changer son mot de passe
     * @param Request $req
     * @param Response $resp
     * @param $args
     * @return Response|void
     */
    public function modifierCompte(Request $req, Response $resp, $args){
        echo($_SESSION['login']);
        $c = Compte::where( 'login', '=', $_SESSION['login'] )->first();
        if(isset($_POST['mdp'])) {
            $c->password = password_hash($_POST['mdp'], PASSWORD_DEFAULT, ['cost' => 12]);
            $c->save();
            session_destroy();
            header('location: /login');
            exit;
        }
        $vue = new Vues\VueCompte($c, $this->container, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }

    /**
     * Méthode affichant tous les créateurs des listes publiques
     * @param Request $req
     * @param Response $resp
     * @param $args
     * @return Response
     */
    public function afficherListeCreateurs(Request $req, Response $resp, $args){
        $listes = Liste::where( 'publique', '=', 1 )->get();
        foreach($listes as $liste){
            if($liste->createur_login != null && $liste->createur_login != "Non identifié"){
                $c = Compte::where( 'login', '=', $liste->createur_login)->first(); 
                $comptes[] = $c;
            }
        }
        $vue = new Vues\VueCreateurs($comptes, $this->container, $req);
        $resp->getBody()->write($vue->render());
        return $resp;
    }
}

