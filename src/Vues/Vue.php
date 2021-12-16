<?php 

namespace App\Vues; 

abstract class Vue{

    private $content; 

    protected $css; 

    public function __construct($content){
        $this->content = $content; 
    }

    public abstract function createContent() : string; 

    public abstract function linkCss() : string; 
    

    public function render(){ 
        $this->content = $this->createContent(); 
        $css = $this->linkCss(); 
        return <<<HTML
            <!DOCTYPE html> 
            <html>
                <head>
                    $css
                </head>
                <header> 
                    <a href="/~cappelli6u/Wish/accueil">Accueil</a> 
                </header>
                <body>
                    <div class="content">  
                        $this->content 
                    </div>
                </body>
            <html>
        HTML;
    } 

    /**
     * Set the value of content
     *
     * @return  self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }   
}