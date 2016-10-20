<?php
if(!defined("FRONT_CONTROLLER"))
{
    throw new Exception();
}

/**
 * Controller par défaut affichant la page d'accueil.
 *
 * Class IndexController
 */
class IndexController implements DefaultController {

    public static function indexAction() {
        require_once (VIEW_DIR.'commons/menu.html');
        require_once (VIEW_DIR.'index.html');
    }
}