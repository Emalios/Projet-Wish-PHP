<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controleurs\ControleurListe as ControleurListe; 
use App\Vues\VueAccueil as VueAccueil; 
use App\Bd\ConnexionFactory as ConnexionFactory;

require __DIR__ . '/vendor/autoload.php';

ConnexionFactory::setConfig("conf.ini.dist"); 
$db = ConnexionFactory::makeConnexion();
$db->setAsGlobal();
$db->bootEloquent();  


$app = new \Slim\App;
$app->get('/liste/{id}', function (Request $req, Response $resp, $args) {
    $controleur = new ControleurListe(); 
    return $controleur->getList($req, $resp, $args);
 });

$app->get('/accueil', function (Request $req, Response $resp, $args) {
    $acc = new VueAccueil();
    return $acc->render();
 });
$app->get('/ajouter-liste', function (Request $req, Response $resp, $args) {  
    $controleur = new ControleurListe(); 
    return $controleur->ajouterListe($req, $resp, $args);
 });

$app->post('/ajouter-liste', function (Request $req, Response $resp, $args) {
   $controleur = new ControleurListe(); 
   return $controleur->ajouterListe($req, $resp, $args);
 })->setName("ajouter-liste");
$app->run();