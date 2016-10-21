<?php
if(!defined("FRONT_CONTROLLER"))
{
    throw new Exception();
}

final class TestController implements DefaultController {

    /**
     * TestController constructor.
     * Prevent anyone to instance a Controller ^_^
     */
    private function __construct() {}

    /**
     * Exemple of default implementation of DefaultController
     */
    public static function indexAction() {
        // TODO: Implement indexAction() method.
    }

    /**
     * Une autre action possible
     */
    public static function showAction() {
        echo 'SHOW CALLED !';
    }

    /**
     * Une autre action possible
     */
    public static function seeAction() {
        echo 'SEE CALLED !';
    }
}