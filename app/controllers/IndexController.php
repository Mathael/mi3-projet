<?php

namespace App\controllers;
use App\utils\Response;

/**
 * Controller par défaut affichant la page d'accueil
 */
final class IndexController implements DefaultController {
    public static function indexAction() {
        return new Response('index');
    }
}