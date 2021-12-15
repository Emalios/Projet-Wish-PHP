<?php 

namespace Style;

class loadCss{

    public static function toHtml(array $links): string {
        $res = "";
        $rootFile = dirname(__DIR__) . DIRECTORY_SEPARATOR;
        if(count($links) > 0){
            foreach($links as $liens){
                $res .= '<link rel="stylesheet" href="/css/' . $liens . '"/>';
            }
        }
        return $res;
    }
}   
?>