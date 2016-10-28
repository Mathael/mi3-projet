<?php
if(!defined("FRONT_CONTROLLER"))
{
    throw new Exception();
}

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
    }

    /**
     * Test du moteur de template:
     *  - Remplacement des {{variable}} par leur valeur
     * http://my-url/?page=test&action=template
     */
    public static function templateAction() {
        // Test du moteur de template
        $template = new TemplateManager('tests/test');
        $template->assign('title', 'ITS WORKS !');
        $template->assign('content', 'Affichage d\'un contenu aléatoire !');
        $template->show();
    }

    /**
     * Test du moteur de template:
     *  - insertion de tableau d'objets {{objet.attribut}} remplacé par la valeur de l'attribut de l'objet
     * http://my-url/?page=test&action=templateArray
     */
    public static function templateArrayAction() {
        $images = ImageDAO::getImageList(10, 5);

        // Test du moteur de template avec tableau de paramètres
        $template = new TemplateManager('tests/test_array');
        $template->assign('title', 'ITS WORKS !');
        $template->assignArrayObjects('content', 'tests/test_array_add', $images);
        $template->show();
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
}