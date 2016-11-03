<?php

namespace App\controllers;

use App\dao\Database;
use App\dao\ImageDAO;
use App\utils\Response;

final class TestController implements DefaultController {

    /**
     * TestController constructor.
     * Empeche quiconque d'instancier le controller ^_^
     */
    private function __construct() {}

    /**
     * Exemple d'implémentation de la méthode obligatoire
     * http://my-url/?page=test
     */
    public static function indexAction() {
        // TODO: Implement indexAction() method.
        return new Response('');
    }

    /**
     * Test du moteur de template:
     *  - Remplacement des {{variable}} par leur valeur
     * http://my-url/?page=test&action=template
     */
    public static function templateAction() {
        // Test du moteur de template
        $response = new Response('tests/test');
        $response->getTemplate()->assignAlpha('title', 'ITS WORKS !');
        $response->getTemplate()->assignAlpha('content', 'Affichage d\'un contenu aléatoire !');
        return $response;
    }

    /**
     * Test du moteur de template:
     *  - insertion de tableau d'objets {{objet.attribut}} remplacé par la valeur de l'attribut de l'objet
     * http://my-url/?page=test&action=templateArray
     */
    public static function templateArrayAction() {
        $images = ImageDAO::getImageList(10, 5);

        // Test du moteur de template avec tableau de paramètres
        $response = new Response('tests/test_array');
        $response->getTemplate()->assignAlpha('title', 'ITS WORKS !');
        $response->getTemplate()->assignAlpha('content', $images);
        return $response;
    }

    /**
     * Une autre action possible
     * http://my-url/?page=test&action=show
     */
    public static function showAction() {
        echo 'SHOW CALLED !';
    }

    /**
     * Une autre action possible
     * http://my-url/?page=test&action=see
     */
    public static function seeAction() {
        echo 'SEE CALLED !';
    }

    public static function connectionClose() {
        Database::close();
    }
}