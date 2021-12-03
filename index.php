<?php

use Illuminate\Database\Capsule\Manager as DB;

$app = new \Slim\App;
$app->get('/games/{id}',
 function (Request $req, Response $resp, $args) {
    print("cc");
 });
$app->post('/games/{id}',
 function (Request $req, Response $resp, $args) {
    print("id");
 });
$app->run();

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
$db->bootEloquent();
