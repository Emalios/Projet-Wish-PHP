<?php 

namespace App\Vues; 

abstract class Vue{

    private $content; 

    protected $container; 

    protected $requete; 

    protected $css; 

    public function __construct($content, $container, $req){
        $this->content = $content; 
        $this->container = $container; 
        $this->requete = $req; 
    }

    public abstract function createContent() : string; 

    public abstract function linkCss() : string; 
    

    public function render(){ 
        $this->content = $this->createContent(); 
        $css = $this->linkCss(); 

        $container = $this->container ;
        $base = $this->requete->getUri()->getBasePath() ;
        $accueil =  $container->router->pathFor( 'accueil') ;
        $publique =  $container->router->pathFor( 'listes_publiques') ;
        $createurs = $container->router->pathFor( 'createurs') ;

        $compte = (isset($_SESSION['login'])) ? "<a href='/mon-compte']>Mon compte</a>" : "<a href='/creer-compte']>Créer compte</a> <a href='/login']>Se connecter</a>";
        
        return <<<HTML
            <!DOCTYPE html> 
            <html>
                <head>
                    $css
                </head>
                <header> 
                    <a href="$accueil">Accueil</a> 
                    <a href="$publique">Listes publiques</a> 
                    <a href="$createurs">Listes des créateurs publiques</a> 
                    $compte
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