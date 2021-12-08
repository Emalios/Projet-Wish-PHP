<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/games/{id}',
 function (Request $req, Response $resp, $args) {
    print("cc");
     $rs = $resp->withStatus( 201 ) ;
     return $rs ;
 });
/*$app->post('/games/{id}',
 function (Request $req, Response $resp, $args) {
    print("id");
 });*/
$app->run();


/*
$db = new DB();
$db->addConnection( [
 'driver' => 'mysql',
 'host' => 'webetu.univ-lorraine.fr',
 'database' => 'database',
 'username' => 'bergerat2u',
 'password' => 'hyth1894948:;',
 'charset' => 'utf8',
 'collation' => 'utf8_unicode_ci',
 'prefix' => ''
] );
$db->setAsGlobal();
$db->bootEloquent();*/
