<?php

namespace App\controllers;
use App\dao\Database;
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

        $categories = ImageDAO::getCategories();

        $response = new Response('admin/index');
        $response->getTemplate()->assign('categories', self::buildCategories($categories));
        return $response;
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
        $response->getTemplate()->assign('id', $imgId);
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

        $response->getTemplate()->assign('Image.id', $image->getId()); // TODO change
        $response->getTemplate()->assign('Image.comment', $image->getComment()); // TODO change
        $response->getTemplate()->assign('Image.category', $image->getCategory()); // TODO change
        $response->getTemplate()->assign('Image.url', $image->getUrl()); // TODO change
        $response->getTemplate()->assign('result', ''); // clean; TODO
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
                        'url' => 'upload/'.$file['name'],
                        'category' => $category,
                        'comment' => $comment
                    ]);
            }
        }

        $categories = ImageDAO::getCategories();

        $response = new Response('admin/image_create_form');
        $response->getTemplate()->assign('categories', self::buildCategories($categories) );
        return $response;
    }

    public static function removeCategory() {
        global $user;
        if($user->getRole() != User::ROLE_ADMIN) return IndexController::indexAction();

        $category = Util::getValue($_POST, 'category', null);
        if(empty($category)) return AdminController::indexAction();

        return ImageDAO::removeCategory($category);
    }

    private static function buildCategories($categories) {
        $res = '';
        foreach ($categories as $category) {
            $res .= '<option value="'.$category.'">'.$category.'</option>';
        }
        return $res;
    }
}