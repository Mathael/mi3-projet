<?php
if(!defined("FRONT_CONTROLLER"))
{
    throw new Exception();
}

final class ImageController implements DefaultController {

    public static function indexAction($params = []) {
        $size = 480; // Default size
        $size *= self::getValue($params, 'size', 1);
        $id = self::getValue($params, 'id', 1);

        $data = new ViewData($size);
        $data->addImage(ImageDAO::getImage($id));

        // Ajout des boutons au menu
        self::buildMenu($params);

        // Appel de la vue associée à l'action
        require_once (VIEW_DIR . 'image.html');
    }

    public static function randomAction($params = []) {
        $size = 480; // Default size
        $size *= self::getValue($params, 'size', 1);

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
        $size = self::getValue($params, 'size', 1);
        $display = self::getValue($params, 'display', 1);

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

    /**
     * Fonction permettant de vérifier qu'une valeur existe bien dans un tableau
     *  si oui : retourne la valeur présente dans le tableau
     *  si non : retourne la valeur par défaut
     * @param $tab
     * @param $key
     * @param $defaultValue
     * @return mixed
     */
    private static function getValue($tab, $key, $defaultValue) {
        if(array_key_exists($key, $tab))
            return $tab[$key];
        return $defaultValue;
    }
}