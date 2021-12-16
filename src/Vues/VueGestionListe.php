<?php 

namespace App\Vues; 

use App\Model\Liste as Liste; 
use App\Model\Item as Item; 

use Style\loadCss;


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
        $links = ['pages/liste.css'];
        return loadCss::toHtml($links);
    }

    public function createContent() : string{ 
        $titre = $this->list["titre"];
        $desc = $this->list["description"];
        $date = $this->list["expiration"];
        $listeItems = "";
        foreach($this->items as $item){ 
            $res = ""; 
            if($item["estReserve"] == null){
                $res = "Il n'est pas réservé";
            } else {
                $res = "Il est réservé";
            }
            $listeItems .= "<p> <strong>" . $item["nom"] . "</strong> " . $item["descr"] . ". Cout = " . $item["tarif"] .
             "</p>\n <img src='/img/" . $item["img"] . "' alt='Image'/> <br> <p> $res </p>" ;
        }
        $html = <<<HTML
            <h1> Liste : $titre </h1>
            <p> $desc </p>
            <p> Date d'expiration : $date </p> 
            $listeItems
        HTML; 
        return $html;
    }
}