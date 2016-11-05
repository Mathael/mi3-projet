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

        // Si l'id n'est pas présent c'est qu'on est arrivée pour la première fois sur la page, on affiche la première image
        $first = $id == null ? ImageDAO::getFirstImage() : null;
        $id = $first->getId();

        $images = $display != 1 ? ImageDAO::getImageList($id, $display) : $first;

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
        $images = $display != 1 ? $images = ImageDAO::getImageList($id + max(1, ($display - 1)), $display) : ImageDAO::getNextImage($id);

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

    private static function assignParameters(Response $response) {
        $response->getTemplate()->assignAlpha('size', 480 * Util::getValue($_GET, 'size', 1));
        $response->getTemplate()->assignAlpha('display', Util::getValue($_GET, 'display', 1));
    }
}