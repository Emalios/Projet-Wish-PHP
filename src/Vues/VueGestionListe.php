<?php 

namespace App\Vues; 

use App\Model\Liste as Liste; 
use App\Model\Item as Item; 


class VueGestionListe extends Vue{


    private $list; 

    private $items; 

    private $url;

    public function __construct($list, $items, $url){ 
        $this->list = $list; 
        $this->items = $items; 
        $this->url = $url;
    } 

    public function linkCss() : string{
        return "";
    }

    public function createContent() : string{ 
        $titre = $this->list["titre"];
        $desc = $this->list["description"];
        $date = $this->list["expiration"];
        $listeItems = "";
        foreach($this->items as $item){ 
            $listeItems .= "<p> <strong>" . $item["nom"] . "</strong> " . $item["descr"] . ". Cout = " . $item["tarif"] .
             "</p>\n <img src=\"/" . $this->url . "img/" . $item["img"] . "\" alt='Image'/>";
        }
        $html = <<<HTML
            <h1> Liste : $titre </h1>
            <a href="/accueil"> Accueil </a>
            <p> $desc </p>
            <p> Date d'expiration : $date </p> 
            $listeItems
        HTML; 
        return $html;
    }
}