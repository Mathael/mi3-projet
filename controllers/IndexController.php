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
final class IndexController implements DefaultController {
    public static function indexAction() {
        $template = new TemplateManager('');
        $template->addTemplateFile('index');
        $template->show();
    }
}