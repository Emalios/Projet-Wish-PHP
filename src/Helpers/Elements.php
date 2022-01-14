<?php

namespace App\Helpers; 


class Elements {
      /**
     * Méthode permettant de faire un dropdown de dates 
     * @param values valeur préremplies 
     * @param string ième dropdown 
     * @return string la valeur du dropdown 
     */
    public static function dateDropDown(string $values = "", string $numero = "") : string{
        $month = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 
        'Octobre', 'Novembre', 'Décembre'];
        $val = "jour" . $numero;
        $res = "<div class='date'>\n\t<select name='$val'>\n";
        $date = [-1,-1,-1];
        if($values != ""){
             $date = explode("-",$values);
        }
        for($i = 1; $i < 32; $i++){
            if($i < 10){
                if($i == intval($date[2]))
                    $res .= "<option value=$i selected='selected'>0$i</option>";
                else
                    $res .= "<option value=$i autocomplete=\"off\">0$i</option>";
            }
            else {
                if($i == intval($date[2]))
                    $res .= "<option value=$i selected>$i</option>";
                else 
                    $res .= "<option value=$i autocomplete=\"off\">$i</option>";
            }
        }
        $res .= "</select>";
        $val = "mois" . $numero;
        $res .= "<select name='$val'>\n";
        for($i = 1; $i < 13; $i++){
            $currentMonth = $month[$i-1];
            if($i == intval($date[1]))
                $res .= "<option value=$i selected>$currentMonth</option>";
            else 
                $res .= "<option value=$i  autocomplete=\"off\">$currentMonth</option>";
        }
        $res .= "</select>";

        $val = "année" . $numero;
        $res .= "\n<select name='$val'>\n";
        for($i = 2035; $i > 2000; $i--){
            if($i == intval($date[0]))
                $res .= "<option value=$i selected>$i</option>";
            else 
                $res .= "<option value=$i autocomplete=\"off\">$i</option>";
        }
        $res .= "</select>\n</div>";
        return $res;
    }
}