<?php
if(!defined("FRONT_CONTROLLER"))
{
    throw new Exception();
}

/**
 * Gestion des requêtes destinées à l'administration des Images et des utilisateurs
 */
final class AdminController implements DefaultController {

    /**
     * http://my-url/?page=admin
     */
    public static function indexAction() {
        if($_SESSION['ROLE'] != User::$ROLE_ADMIN) {
            IndexController::indexAction();
        }

        $template = new TemplateManager('admin/index');
        $template->show();
    }

    public static function addImageAction($params = []) {

    }

    public static function removeImageAction($params = []) {

    }

    public static function editImageAction($params = []) {

    }
}