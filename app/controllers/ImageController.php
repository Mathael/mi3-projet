<?php

namespace App\controllers;

use App\dao\ImageDAO;
use App\model\Image;
use App\model\User;
use App\utils\Response;
use App\utils\Util;

/**
 * Class ImageController
 * @author Lucas Georges
 * @package App\controllers
 */
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
        $response->getTemplate()->assign('images', $images);
        $response->getTemplate()->assign('id', is_array($images) ? $images[0]->getId() : $images->getId());
        $response->getTemplate()->assign('options', self::buildCategory());
        self::assignParameters($response);
        return $response;
    }

    public static function lastAction() {
        $image = ImageDAO::findLast();

        if($image == null) {
            return self::indexAction();
        }

        $response = new Response('image/image');
        $response->getTemplate()->assign('images', $image);
        $response->getTemplate()->assign('options', self::buildCategory());
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
        $response->getTemplate()->assign('images', $images);
        $response->getTemplate()->assign('id', is_array($images) ? $images[0]->getId() : $images->getId());
        $response->getTemplate()->assign('options', self::buildCategory());
        self::assignParameters($response);
        return $response;
    }

    public static function nextAction() {
        $id = Util::getValue($_GET, 'id', 1);
        $display = Util::getValue($_GET, 'display', 1);

        /** @var Image|Image[] $images */
        $images = $display != 1 ? $images = ImageDAO::getImageList($id+$display, $display) : ImageDAO::getNextImage($id);

        if(empty($images)) {
            return IndexController::indexAction();
        }

        // Appel de la vue associée à l'action
        $response = new Response('image/image');
        $response->getTemplate()->assign('id', is_array($images) ? $images[0]->getId() : $images->getId());
        $response->getTemplate()->assign('images', $images);
        $response->getTemplate()->assign('options', self::buildCategory());
        self::assignParameters($response);
        return $response;
    }

    public static function voteAction() {
        global $user;
        if($user->getRole() == User::ROLE_ANONYMOUS) {
            return IndexController::indexAction();
        }

        $id = Util::getValue($_GET, 'id', null);
        $vote = Util::getValue($_GET, 'stars', null);

        if($id != null && $vote != null) ImageDAO::proceedVote($id, $vote); // TODO: AJAX notification success/fail

        return self::indexAction();
    }

    public static function previousAction() {
        $id = Util::getValue($_GET, 'id', 1);
        $display = Util::getValue($_GET, 'display', 1);

        /** @var Image|Image[] $images */
        $images = $display != 1 ? $images = ImageDAO::getImageList($id-$display, $display) : ImageDAO::getPrevImage($id);

        if(empty($images)) {
            return IndexController::indexAction();
        }

        // Appel de la vue associée à l'action
        $response = new Response('image/image');
        $response->getTemplate()->assign('id', is_array($images) ? $images[0]->getId() : $images->getId());
        $response->getTemplate()->assign('images', $images);
        $response->getTemplate()->assign('options', self::buildCategory());
        self::assignParameters($response);
        return $response;
    }

    public static function moreAction() {
        $display = Util::getValue($_GET, 'display', 1);
        $_GET['display'] = $display *= 2;
        return self::indexAction();
    }

    public static function lessAction() {
        $display = Util::getValue($_GET, 'display', 1);
        $_GET['display'] = max(1, $display /= 2);
        return self::indexAction();
    }

    public static function categoryAction(){
        $category = Util::getValue($_POST, 'category', null);

        if($category == null || $category == 'null') return self::indexAction();

        $images = ImageDAO::getImageByCategorie($category);

        // Appel de la vue associée à l'action
        $response = new Response('image/image');
        $response->getTemplate()->assign('images', $images);
        $response->getTemplate()->assign('options', self::buildCategory());
        self::assignParameters($response);
        $response->getTemplate()->assign('id', is_array($images) ? $images[0]->getId() : $images->getId());
        return $response;
    }

    // Not used
    public static function zoominAction() {
        $size = Util::getValue($_GET, 'size', 1);
        $_GET['size'] = $size += 0.25;
        return self::indexAction();
    }

    // Not used
    public static function zoomoutAction() {
        $size = Util::getValue($_GET, 'size', 1);
        $_GET['size'] = $size -= 0.25;
        return self::indexAction();
    }

    private static function buildCategory(){
        $categories  = ImageDAO::getCategories();
        $options = '<option value="null">Aucun</option>';

        foreach ($categories as $category){
            $options .= '<option value="'.$category.'">'.$category.'</option>';
        }
        return $options;
    }

    private static function assignParameters(Response $response) {
        $response->getTemplate()->assign('size', 480 * Util::getValue($_GET, 'size', 1));
        $response->getTemplate()->assign('display', Util::getValue($_GET, 'display', 1));
    }
}