<?php

namespace App\controllers;

use App\dao\ImageDAO;
use App\model\User;
use App\utils\TemplateManager;
use App\utils\Util;

final class ImageController implements DefaultController {

    public static function indexAction($params = []) {
        $size = 480; // Default size
        $size *= Util::getValue($params, 'size', 1);
        $id = Util::getValue($params, 'id', 1);
        $display = Util::getValue($params, 'display', 1);

        $images = [];
        if($display != 1) {
            $images = ImageDAO::getImageList($id, $display);
        } else $images[] = ImageDAO::getFirstImage();


        if(!$images) {
            die('La ou les images sont inexistantes dans la base de données.'); // TODO: change me
        }

        // Ajout des boutons au menu
        $menu = self::buildMenu($params);

        //Ajout de la lite des category
        $option = self::buildCategory();

        // Appel de la vue associée à l'action
        $template = new TemplateManager('image/image');

        // Les admins peuvent voir des boutons supplémentaires sur la page
        if($_SESSION['ROLE'] == User::$ROLE_ADMIN)
            $template->assignArrayObjects('images', 'image/image_small_admin', $images);
        else
           $template->assignArrayObjects('images', 'image/image_small', $images);

        $template->assign('size', $size);
        $template->assignArray($menu);
        $template->assign('options',$option);
        $template->show();
    }

    public static function randomAction($params = []) {
        $size = 480; // Default size
        $size *= Util::getValue($params, 'size', 1);
        $display = Util::getValue($params, 'display', 1);

        $images = [];
        if($display != 1) {
            $images = ImageDAO::getRandomImageList($display);
        } else $images[] = ImageDAO::getRandomImage();

        if(!$images) {
            //die('L\'image est inexistante dans la base de données.'); // TODO: change me
        }

        $params['id'] = $images[0]->getId();

        // Ajout des boutons au menu
        $menu = self::buildMenu($params);

        //Ajout de la lite des category
        $option = self::buildCategory();

        // Appel de la vue associée à l'action
        $template = new TemplateManager('image/image');

        // Les admins peuvent voir des boutons supplémentaires sur la page
        if($_SESSION['ROLE'] == User::$ROLE_ADMIN)
            $template->assignArrayObjects('images', 'image/image_small_admin', $images);
        else
            $template->assignArrayObjects('images', 'image/image_small', $images);

        $template->assign('size', $size);
        $template->assignArray($menu);
        $template->assign('options',$option);
        $template->show();
    }

    /**
     * Construction du menu additionnel en préservant les paramètres [size] et [display]
     * @param array $params
     * @return array
     */
    private static function buildMenu($params = []) {
        $size = Util::getValue($params, 'size', 1);
        $display = Util::getValue($params, 'display', 1);
        $imgId = Util::getValue($params, 'id', ImageDAO::getFirstImage()->getId());

        return [
            'first' => '?page=image&id='.$imgId.'&size='.$size.'&display='.$display,
            'random' => '?page=image&action=random&id='.$imgId.'size='.$size.'&display='.$display,
            'more' => '?page=image&action=more&id='.$imgId.'&size='.$size.'&display='.$display * 2,
            'less' => '?page=image&action=less&id='.$imgId.'&size='.$size.'&display='.max(1, $display / 2),
            'zoom +' => '?page=image&id='.$imgId.'&size='.($size+0.25).'&display='.$display,
            'zoom -' => '?page=image&id='.$imgId.'&size='.($size-.25).'&display='.$display
        ];
    }

    /**
     * Construction du formulaire listant les différentes catégories d'images
     * @return string
     */
    private static function buildCategory(){
        $categories  = ImageDAO::getCategories();
        $options = '';

        foreach ($categories as $category){
            $options .= '<option value="'.$category.'">'.$category.'</option>';
        }
        return $options;
    }

    public static function categoryAction($params = []){

        $category = $_POST['category'];

        $images = ImageDAO::getImageByCategorie($category);

        //Ajout de la lite des category
        $option = self::buildCategory();

        // Appel de la vue associée à l'action
        $template = new TemplateManager('image/image');

        // Ajout des boutons au menu
        $menu = self::buildMenu($params);

        // Appel des images
        if($_SESSION['ROLE'] == User::$ROLE_ADMIN)
            $template->assignArrayObjects('images', 'image/image_small', $images);
        else
            $template->assignArrayObjects('images', 'image/image_small_admin', $images);

        //Ajout des images de la catégorie
        $template->assignArrayObjects('images', 'image/image_small', $images);
        $template->assign('options',$option);
        $template->assignArray($menu);
        $template->show();

    }
}