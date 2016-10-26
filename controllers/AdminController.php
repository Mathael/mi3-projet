<?php
if(!defined("FRONT_CONTROLLER"))
{
    throw new Exception();
}

/**
 * Gestion des requêtes destinées à l'administration des Images et des utilisateurs
 */
final class AdminController implements DefaultController {

    /**
     * http://my-url/?page=admin
     */
    public static function indexAction() {
        if($_SESSION['ROLE'] != User::$ROLE_ADMIN) {
            IndexController::indexAction();
            return;
        }
    }

    /**
     * http://my-url/?page=admin&action=addImage
     */
    public static function addImageAction() {
        if($_SESSION['ROLE'] != User::$ROLE_ADMIN) {
            IndexController::indexAction();
            return;
        }
    }

    /**
     * http://my-url/?page=admin&action=removeImage
     */
    public static function removeImageAction() {
        if($_SESSION['ROLE'] != User::$ROLE_ADMIN) {
            IndexController::indexAction();
            return;
        }

        $imgId = Util::getValue($_GET, 'imageId', null);
        if($imgId == null) {
            // TODO: do somethings
            ImageController::indexAction();
            return;
        }

        $resultPage = NULL;
        $resultPage = ImageDAO::delete($imgId) ? 'admin/image_remove_success' : 'admin/image_remove_fail';

        $template = new TemplateManager($resultPage);
        $template->assign('id', $imgId);
        $template->show();
    }

    /**
     * http://my-url/?page=admin&action=editImage
     */
    public static function editImageAction() {
        if($_SESSION['ROLE'] != User::$ROLE_ADMIN) {
            IndexController::indexAction();
            return;
        }

        $imgId = Util::getValue($_GET, 'imageId', null);
        if($imgId == null) {
            // TODO: do somethings
            ImageController::indexAction();
            return;
        }

        $image = ImageDAO::getImage($imgId);
        if($image == null) {
            // TODO: do somethings
            ImageController::indexAction();
            return;
        }

        $template = new TemplateManager('admin/image_edit');

        if(!empty($_POST))
        {
            $comment = Util::getValue($_POST, 'comment', $image->getComment());
            $category = Util::getValue($_POST, 'category', $image->getCategory());
            if(ImageDAO::edit($imgId, $comment, $category)) {
                $template->assignTemplate('result', 'admin/image_edit_success');
                $image = new Image([
                    'id' => $image->getId(),
                    'comment' => $comment,
                    'url' => $image->getUrl(),
                    'category' => $category
                ]);
            } else {
                $template->assignTemplate('result', 'admin/image_edit_fail');
            }
        }

        $template->assignObject($image);
        $template->assign('result', ''); // clean; TODO
        $template->show();
    }
}