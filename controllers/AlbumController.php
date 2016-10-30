<?php

namespace App\controllers;
use App\model\Album;
use App\dao\AlbumDao;
use App\utils\TemplateManager;
use App\utils\Util;

/**
 * @author LEBOC Philippe.
 * Date: 28/10/2016
 * Time: 22:32
 *
 * TODO: Check USER ACCESS
 */
final class AlbumController implements DefaultController
{
    /**
     * Depuis l'index, l'utilisateur peut :
     * - Voir la liste de ses albums
     * - Consulter un album via un bouton sur un des albums de l'utilisateur
     * - Editer un album via un bouton sur un des albums de l'utilisateur
     * - Créer un album via un bouton
     */
    public static function indexAction()
    {
        $template = new TemplateManager('album/index');
        $albums = AlbumDao::findAllByOwnerId($_SESSION['user_id']);

        if($albums != null)
        {
            $template->assignAlpha('albums', $albums);
        }

        $template->assign('albums', 'Vous n\'avez pas d\'albums !');
        $template->show();
    }

    public static function createAction() {
        $name = Util::getValue($_POST, 'name', null);

        if(!empty($name)) {
            AlbumDao::create([
                'name' => $name,
                'ownerId' => $_SESSION['user_id']
            ]);
            self::indexAction();
            return;
        }

        $template = new TemplateManager('album/create_form');
        $template->show();
    }

    public static function showAction() {
        $id = Util::getValue($_GET, 'id', null);
        if($id == null)
        {
            self::indexAction();
            return;
        }

        $template = new TemplateManager('album/show');
        $album = AlbumDao::findByIdAndOwnerId($_SESSION['user_id']);

        if($album != null)
        {
            $template->assignAlpha('name', $album->getName());
            $template->assignAlpha('images', $album->getImages());
        }

        $template->assign('album', 'Vous n\'avez pas d\'albums !');
        $template->show();
    }

    public static function addImageAction() {
        $image = Util::getValue($_POST, 'image', null);
        $album = Util::getValue($_POST, 'album', null);
        if($image == null || $album == null){
            self::indexAction();
            return;
        }

        $lastIndex = AlbumDao::getLastIndex($album, $image);

        AlbumDao::insertImage($album, $image, $lastIndex+1);

        ImageController::indexAction();
        return;
    }

    public static function addToAlbumAction() {
        $image = Util::getValue($_GET, 'image', null);

        $select = '';
        $userId = $_SESSION['user_id'];
        if(!empty($userId))
        {
            $albums = AlbumDao::findAllByOwnerId($userId); /** @var $albums Album[] */
            if(!empty($albums))
            {
                $select .= '<select name=\'album\'>';
                foreach($albums as $album)
                {
                    $select .= '<option value=\''.$album->getId().'\' >'.$album->getName().'</option>';
                }
                $select .= '</select>';
            }
        }

        $template = new TemplateManager('album/choose_album');
        $template->assign('select', $select);
        $template->assign('image', $image);
        $template->show();
    }
}