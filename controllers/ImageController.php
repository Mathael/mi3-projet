<?php
if(!defined("FRONT_CONTROLLER"))
{
    throw new Exception();
}

class ImageController implements DefaultController {

    public static function indexAction($params = []) {
        $size = 480; // Default size
        $size *= Util::getValue($params, 'size', 1);
        $id = Util::getValue($params, 'id', 1);

        $data = new ViewData($size);
        $data->addImage(ImageDAO::getImage($id));

        // Ajout des boutons au menu
        self::buildMenu($params);

        // Appel de la vue associée à l'action
        require_once (VIEW_DIR . 'image.html');
    }

    public static function randomAction($params = []) {
        $size = 480; // Default size
        $size *= Util::getValue($params, 'size', 1);

        $data = new ViewData($size);
        $data->addImage(ImageDAO::getRandomImage());

        // Ajout des boutons au menu
        self::buildMenu($params);

        // Appel de la vue associée à l'action
        require_once (VIEW_DIR . 'image.html');
    }

    /**
     * Construction du menu additionnel en préservant les paramètres [size] et [display]
     * @param array $params
     */
    private static function buildMenu($params = []) {
        $size = Util::getValue($params, 'size', 1);
        $display = Util::getValue($params, 'display', 1);

        $menu = [
            'first' => '?page=image&size='.$size.'&display='.$display,
            'random' => '?page=image&action=random&size='.$size.'&display='.$display,
            'more' => '?page=image&action=more&size='.$size.'&display='.$display,
            'less' => '?page=image&action=less&size='.$size.'&display='.$display,
            'zoom +' => '?page=image&action=zoomin&size='.$size.'&display='.$display,
            'zoom -' => '?page=image&action=zoomout'
        ];
        require_once (VIEW_DIR . 'commons/menu.html');
    }
}