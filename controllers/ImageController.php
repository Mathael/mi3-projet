<?php
if(!defined("FRONT_CONTROLLER"))
{
    throw new Exception();
}

class ImageController implements DefaultController {

    public static function indexAction() {
        $data = new ViewData();
        $data->addImage(ImageDAO::getFirstImage());

        // Ajout des boutons au menu
        self::buildMenu();

        // Appel de la vue associée à l'action
        require_once (VIEW_DIR . 'image.html');
    }

    public static function randomAction() {
        $data = new ViewData();
        $data->addImage(ImageDAO::getRandomImage());

        // Ajout des boutons au menu
        self::buildMenu();

        // Appel de la vue associée à l'action
        require_once (VIEW_DIR . 'image.html');
    }

    private static function buildMenu() {
        $menu = [
            'first' => '?page=image',
            'random' => '?page=image&action=random',
            'more' => '?page=image&action=more',
            'less' => '?page=image&action=less',
            'zoom +' => '?page=image&action=zoomin',
            'zoom -' => '?page=image&action=zoomout'
        ];
        require_once (VIEW_DIR . 'commons/menu.html');
    }
}