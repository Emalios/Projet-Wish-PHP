<?php

namespace App\Vues; 

class VueListesPubliques extends Vue{

    private $listes; 

    public function __construct($listes, $c, $rq){
        parent::__construct("", $c, $rq);	
        $this->listes = $listes;
    }

    public function linkCss() : string{
        return "";
    }
    
    public function createContent() : string{
        $res = ""; 
        foreach($this->listes as $liste){
            $path =  $this->container->router->pathFor( 'liste', ["token" => $liste->token]) ;
            $res .= "<p> " . $liste->titre . "</p><a href='$path'>Voir</a><br>";
        }
        return $res;
    }
}