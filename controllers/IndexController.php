<?php

namespace App\controllers;

use App\utils\TemplateManager;

/**
 * Controller par défaut affichant la page d'accueil
 */
final class IndexController implements DefaultController {
    public static function indexAction() {
        $template = new TemplateManager('index');
        $template->show();
    }
}