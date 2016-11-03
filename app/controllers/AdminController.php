<?php

namespace App\controllers;
use App\dao\ImageDAO;
use App\model\Image;
use App\model\User;
use App\utils\Response;
use App\utils\Util;

/**
 * Gestion des requêtes destinées à l'administration des Images et des utilisateurs
 */
final class AdminController implements DefaultController {

    /**
     * http://my-url/?page=admin
     */
    public static function indexAction() {
        global $user;
        if($user->getRole() != User::ROLE_ADMIN) {
            return IndexController::indexAction();
        }

        return new Response('admin/index');
    }

    /**
     * http://my-url/?page=admin&action=addImage
     */
    public static function addImageAction() {
        global $user;
        if($user->getRole() != User::ROLE_ADMIN) {
            return IndexController::indexAction();
        }

        // WHERE IS MY CODE ?? !!!!!!!!
    }

    /**
     * http://my-url/?page=admin&action=removeImage
     */
    public static function removeImageAction() {
        global $user;
        if($user->getRole() != User::ROLE_ADMIN) {
            return IndexController::indexAction();
        }

        $imgId = Util::getValue($_GET, 'imageId', null);
        if($imgId == null) {
            // TODO: do somethings
            return ImageController::indexAction();
        }

        $resultPage = NULL;
        $resultPage = ImageDAO::delete($imgId) ? 'admin/image_remove_success' : 'admin/image_remove_fail';

        $response = new Response($resultPage);
        $response->getTemplate()->assignAlpha('id', $imgId);
        return $response;
    }

    /**
     * http://my-url/?page=admin&action=editImage
     */
    public static function editImageAction() {
        global $user;
        if($user->getRole() != User::ROLE_ADMIN) {
            return IndexController::indexAction();
        }

        $imgId = Util::getValue($_GET, 'imageId', null);
        if($imgId == null) {
            // TODO: do somethings
            return ImageController::indexAction();
        }

        $image = ImageDAO::findById($imgId);
        if($image == null) {
            // TODO: do somethings
            return ImageController::indexAction();
        }

        $response = new Response('admin/image_edit');

        if(!empty($_POST))
        {
            $comment = Util::getValue($_POST, 'comment', $image->getComment());
            $category = Util::getValue($_POST, 'category', $image->getCategory());
            if(ImageDAO::edit($imgId, $comment, $category)) {
                $response->getTemplate()->assignTemplate('result', 'admin/image_edit_success');
                $image = new Image([
                    'id' => $image->getId(),
                    'comment' => $comment,
                    'url' => $image->getUrl(),
                    'category' => $category
                ]);
            } else {
                $response->getTemplate()->assignTemplate('result', 'admin/image_edit_fail');
            }
        }

        $response->getTemplate()->assignAlpha('Image.id', $image->getId()); // TODO change to assign normal (need refactor template manager)
        $response->getTemplate()->assignAlpha('result', ''); // clean; TODO
        return $response;
    }

    /**
     * http://my-url/?page=admin&action=createImageForm
     */
    public static function createImageFormAction() {
        global $user;
        if($user->getRole() != User::ROLE_ADMIN) {
            return IndexController::indexAction();
        }

        if(!empty($_FILES['file']) && !empty($_POST))
        {
            $file = $_FILES['file'];
            if(move_uploaded_file($file['tmp_name'], UPLOAD_DIR.$file['name'])) {
                $category = Util::getValue($_POST, 'category', null);
                $comment = Util::getValue($_POST, 'comment', null);

                if($category != null && $comment != null)
                    ImageDAO::create([
                        'url' => $file['name'],
                        'category' => $category,
                        'comment' => $comment
                    ]);
            }
        }

        $categories = ImageDAO::getCategories();

        $response = new Response('admin/image_create_form');
        $response->getTemplate()->assignArrayTemplate('categories', 'admin/image_create_form_small', 'category', $categories);
        return $response;
    }
}