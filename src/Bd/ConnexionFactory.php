<?php

namespace App\Bd; 

use Illuminate\Database\Capsule\Manager as DB;

class ConnexionFactory{

    private static $host, $pass, $user, $db; 

    private static $connexion;

    /**
     * méthode définissant les paramètres personnels à appliquer au projet
     * @param $file fichier de config
     * @return void
     */
    public static function setConfig($file){
        $init = parse_ini_file($file);
        static::$host = $init['host']; 
        static::$pass = $init['password']; 
        static::$user = $init['username']; 
        static::$db = $init['database']; 
    }

    /**
     * méthode effectuant une connection à la base de donnée
     * @return connection à la base de donnée avec les paramètres établies
     */
    public static function makeConnexion(){
        if(static::$connexion == null){
            $db = new DB();
            $db->addConnection( [
                'driver' => 'mysql',
                'host' => static::$host,
                'database' => static::$db,
                'username' => static::$user,
                'password' => static::$pass,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => ''
                
            ]);
            static::$connexion = $db; 
        } 
        return static::$connexion;
    }
}

?>