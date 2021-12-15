<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controleurs\ControleurListe as ControleurListe; 
use Illuminate\Database\Capsule\Manager as DB;
use App\Vues\VueAccueil as VueAccueil; 

require __DIR__ . '/vendor/autoload.php';


$db = new DB();
$db->addConnection( [
 'driver' => 'mysql',
 'host' => 'localhost',
 'database' => 'wish',
 'username' => 'root',
 'password' => 'root',
 'charset' => 'utf8',
 'collation' => 'utf8_unicode_ci',
 'prefix' => ''
] );
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
/*$app->post('/games/{id}',
 function (Request $req, Response $resp, $args) {
    print("id");
 });*/
$app->run();