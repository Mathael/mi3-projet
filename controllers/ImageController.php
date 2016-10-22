<?php
if(!defined("FRONT_CONTROLLER"))
{
    throw new Exception();
}

final class ImageController implements DefaultController {

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

        $image = ImageDAO::getRandomImage();

        $params['id'] = $image->getId();

        $data = new ViewData($size);
        $data->addImage($image);

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
        $imgId = Util::getValue($params, 'id', 1);

        $menu = [
            'first' => '?page=image&size='.$size.'&display='.$display,
            'random' => '?page=image&action=random&&id='.$imgId.'size='.$size.'&display='.$display,
            'more' => '?page=image&action=more&id='.$imgId.'&size='.$size.'&display='.$display * 2,
            'less' => '?page=image&action=less&id='.$imgId.'&size='.$size.'&display='.max(1, $display / 2),
            'zoom +' => '?page=image&id='.$imgId.'&size='.($size*1.25).'&display='.$display,
            'zoom -' => '?page=image&id='.$imgId.'&size='.($size*0.75).'&display='.$display
        ];
        require_once (VIEW_DIR . 'commons/menu.html');
    }
}