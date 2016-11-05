<?php

namespace App\controllers;

use App\dao\ImageDAO;
use App\model\Image;
use App\utils\Response;
use App\utils\Util;

final class ImageController implements DefaultController {

    public static function indexAction() {
        $id = Util::getValue($_GET, 'id', null);
        $display = Util::getValue($_GET, 'display', 1);

        $first = null;
        if($id == null) {
            $first = ImageDAO::getFirstImage();
            if(empty($first)) return IndexController::indexAction(); // No image in database
            $id = $first->getId();
        }

        $images = null;
        if($display != 1) {
            $images = ImageDAO::getImageList($id, $display);
        } else {
            $images = $first == null ? ImageDAO::findById($id) : $first;
        }

        if(empty($images)) {
            return IndexController::indexAction();
        }

        // Appel de la vue associée à l'action
        $response = new Response('image/image');
        $response->getTemplate()->assignAlpha('images', $images);
        $response->getTemplate()->assignAlpha('id', is_array($images) ? $images[0]->getId() : $images->getId());
        self::assignParameters($response);
        return $response;
    }

    public static function randomAction() {
        $display = Util::getValue($_GET, 'display', 1);

        /** @var Image|Image[] $images */
        $images = $display != 1 ? $images = ImageDAO::getRandomImageList($display) : ImageDAO::getRandomImage();

        if(empty($images)) {
            return IndexController::indexAction();
        }

        // Appel de la vue associée à l'action
        $response = new Response('image/image');
        $response->getTemplate()->assignAlpha('images', $images);
        $response->getTemplate()->assignAlpha('id', is_array($images) ? $images[0]->getId() : $images->getId());
        self::assignParameters($response);
        return $response;
    }

    public static function nextAction() {
        $id = Util::getValue($_GET, 'id', 1);
        $display = Util::getValue($_GET, 'display', 1);

        /** @var Image|Image[] $images */
        $images = $display != 1 ? $images = ImageDAO::getImageList($id+1, $display) : ImageDAO::getNextImage($id);

        if(empty($images)) {
            return IndexController::indexAction();
        }

        // Appel de la vue associée à l'action
        $response = new Response('image/image');
        $response->getTemplate()->assignAlpha('id', is_array($images) ? $images[0]->getId() : $images->getId());
        $response->getTemplate()->assignAlpha('images', $images);
        self::assignParameters($response);
        return $response;
    }

    public static function voteAction() {
        $id = Util::getValue($_GET, 'id', null);
        $vote = Util::getValue($_GET, 'stars', null);

        if($id != null && $vote != null) ImageDAO::proceedVote($id, $vote); // TODO: AJAX notification success/fail

        return self::indexAction();
    }

    private static function assignParameters(Response $response) {
        $response->getTemplate()->assignAlpha('size', 480 * Util::getValue($_GET, 'size', 1));
        $response->getTemplate()->assignAlpha('display', Util::getValue($_GET, 'display', 1));
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

    public static function categoryAction(){
        $category = Util::getValue($_POST, 'category', null);

        if($category == null) return self::indexAction();

        $images = ImageDAO::getImageByCategorie($category);

        //Ajout de la lite des category
        $option = self::buildCategory();

        // Appel de la vue associée à l'action
        $response = new Response('image/image');
        $response->getTemplate()->assignAlpha('images', $images);
        $response->getTemplate()->assignAlpha('options',$option);
        self::assignParameters($response);
        return $response;
    }
}