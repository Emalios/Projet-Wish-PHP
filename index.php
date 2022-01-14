<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controleurs\ControleurListe as ControleurListe; 
use App\Controleurs\ControleurItem as ControleurItem; 
use App\Controleurs\ControleurCompte as ControleurCompte; 
use App\Vues\VueAccueil as VueAccueil; 
use App\Bd\ConnexionFactory as ConnexionFactory;

require __DIR__ . '/vendor/autoload.php';

session_start();
ConnexionFactory::setConfig("conf.ini.dist"); 
$db = ConnexionFactory::makeConnexion();
$db->setAsGlobal();
$db->bootEloquent();  

$configuration = ['settings' => [] ] ;
$c = new \Slim\Container($configuration);

$app = new \Slim\App;

$app->get('/liste/{id}', function (Request $req, Response $resp, $args) {
   $controleur = new ControleurListe($this); 
   return $controleur->getList($req, $resp, $args);
})->setName("liste");
$app->post('/liste/{id}', function (Request $req, Response $resp, $args) {  
   $controleur = new ControleurListe($this); 
   return $controleur->getList($req, $resp, $args);
 });

$app->get('/accueil', function (Request $req, Response $resp, $args) {
   $acc = new VueAccueil($this, $req);
   return $acc->render();
 })->setName('accueil');

$app->get('/ajouter-liste', function (Request $req, Response $resp, $args) {  
    $controleur = new ControleurListe($this); 
    return $controleur->ajouterListe($req, $resp, $args);
})->setName('ajouter-liste');

$app->post('/ajouter-liste', function (Request $req, Response $resp, $args) {
   $controleur = new ControleurListe($this); 
   return $controleur->ajouterListe($req, $resp, $args);
});

$app->get('/modifier-liste/{id}', function (Request $req, Response $resp, $args) {  
    $controleur = new ControleurListe($this); 
    return $controleur->modifierListe($req, $resp, $args);
})->setName('modifier-liste');

$app->post('/modifier-liste/{id}', function (Request $req, Response $resp, $args) {
   $controleur = new ControleurListe($this); 
   return $controleur->modifierListe($req, $resp, $args);
})->setName("modifier-liste");

$app->get('/modifier-item/{id}', function (Request $req, Response $resp, $args) {  
    $controleur = new ControleurItem($this); 
    return $controleur->modifierItem($req, $resp, $args);
})->setName("modifier-item");

$app->post('/modifier-item/{id}', function (Request $req, Response $resp, $args) {
   $controleur = new ControleurItem($this); 
   return $controleur->modifierItem($req, $resp, $args);
})->setName("modifier-liste");

$app->get('/item/{id}', function (Request $req, Response $resp, $args) {  
   $controleur = new ControleurItem($this); 
   return $controleur->getItem($req, $resp, $args);
})->setName("item");

$app->post('/item/{id}', function (Request $req, Response $resp, $args) {  
   $controleur = new ControleurItem($this); 
   return $controleur->getItem($req, $resp, $args);
});

$app->get('/creer-compte', function (Request $req, Response $resp, $args) {  
   $controleur = new ControleurCompte($this); 
   return $controleur->creerCompte($req, $resp, $args);
});
$app->post('/creer-compte', function (Request $req, Response $resp, $args) {  
   $controleur = new ControleurCompte($this); 
   return $controleur->creerCompte($req, $resp, $args);
})->setName("creer-compte");

$app->get('/login', function (Request $req, Response $resp, $args) {  
   $controleur = new ControleurCompte($this); 
   return $controleur->seConnecter($req, $resp, $args);
})->setName('login');

$app->post('/login', function (Request $req, Response $resp, $args) {  
   $controleur = new ControleurCompte($this); 
   return $controleur->seConnecter($req, $resp, $args);
});

$app->get('/mon-compte', function (Request $req, Response $resp, $args) {  
   $controleur = new ControleurCompte($this); 
   return $controleur->gestionCompte($req, $resp, $args);
})->setName("mon-compte");

$app->post('/mon-compte', function (Request $req, Response $resp, $args) {  
   $controleur = new ControleurCompte($this); 
   return $controleur->gestionCompte($req, $resp, $args);
});

$app->get('/modifier-compte', function (Request $req, Response $resp, $args) {  
   $controleur = new ControleurCompte($this); 
   return $controleur->modifierCompte($req, $resp, $args);
})->setName("modifier-compte");
$app->post('/modifier-compte', function (Request $req, Response $resp, $args) {  
   $controleur = new ControleurCompte($this); 
   return $controleur->modifierCompte($req, $resp, $args);
});

$app->get('/listes-publiques', function (Request $req, Response $resp, $args) {  
   $controleur = new ControleurListe($this); 
   return $controleur->afficherListesPubliques($req, $resp, $args);
})->setName("listes_publiques");

$app->get('/listes-createurs', function (Request $req, Response $resp, $args) {  
   echo($this);
   $controleur = new ControleurCompte($this); 
   return $controleur->afficherListeCreateurs($req, $resp, $args);
})->setName("createurs");



$app->run();