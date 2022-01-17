<?php

namespace App\Vues; 

class VueListesPubliques extends Vue{

    private $listes; 

    public function __construct($listes, $c, $rq){
        parent::__construct("", $c, $rq);	
        $this->listes = $listes;
    }

    public function linkCss() : array{
        return [];
    }
    
    public function createContent() : string{
        $res = ""; 
        foreach($this->listes as $liste){
            $path =  $this->container->router->pathFor( 'liste', ["token" => $liste->token]) ;
            $res .= "<p> " . $liste->titre . "</p><a href='$path'>Voir</a><br>";
        }
        return <<<HTML
            <h1 class="subtitle"> Liste publiques </h1>
            <h3 style="text-align: center;"> Vous pouvez voir ici les listes publiées publiquement.</h3>
            $res
        HTML;
    }
}