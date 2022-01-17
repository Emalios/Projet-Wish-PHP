<?php 

namespace App\Vues; 
use Style\loadCss as loadCss;

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

    /**
     * fonction abstraite servant à définir comment afficher la vue
     * @return string
     */
    public abstract function createContent() : string;

    /**
     * fonction abstraite permettant de relier un css custom à chaque vue
     * @return string
     */
    public abstract function linkCss() : array;


    /**
     * Méthode permettant de compiler la vue vers un format affichable
     * @return code HTML représentant la vue
     */
    public function render(){ 
        $this->content = $this->createContent(); 
        $links = $this->linkCss(); 

        array_push($links,"elements/basic.css");
        array_push($links,"elements/text.css");

        $css = loadCss::toHtml($links);

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
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    $css
                </head>
                <header> 
                    <div class="sommaire">
                        <div class="gauche">
                            <h1 class="header-title"> Wish Liste </h1>
                            <img src="$base/img/icon.png" alt="Icone" class="profil">
                        </div>
                        <div id="hamburger">
                            <div id="hamburger-content">
                                <nav>
                                    <ul class="details">
                                        <li><a href="$accueil">Accueil</a></li>
                                        <li><a href="$publique">Listes publiques</a></li>
                                        <li><a href="$createurs">Listes des créateurs publiques</a></li>
                                        <li>$compte</li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </header>
                <body>
                    <div class="page">
                        <div class="page-container">
                            $this->content
                        </div>
                        <div id="footer">
                            <div class="liensFooter">
                                <div class="lFooter">
                                    <p class="copyR">	Copyright &copy; Bravo | 2021 - 2022 | All Right Reserved.</p>
                                </div>
                            </div>
                        </div>
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