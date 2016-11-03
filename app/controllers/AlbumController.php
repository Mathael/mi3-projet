<?php

namespace App\controllers;
use App\dao\AlbumDao;
use App\model\User;
use App\utils\Response;
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
        global $user;
        if($user->getRole() == User::ROLE_ANONYMOUS) {
            return IndexController::indexAction();
        }

        $response = new Response('album/index');
        $albums = AlbumDao::findAllByOwnerId($user->getId());

        if($albums != null)
        {
            $response->getTemplate()->assignAlpha('albums', $albums);
        }

        $response->getTemplate()->assignAlpha('albums', 'Vous n\'avez pas d\'albums !');
        return $response;
    }

    public static function createAction() {
        global $user;
        $name = Util::getValue($_POST, 'name', null);

        if(!empty($name)) {
            AlbumDao::create([
                'name' => $name,
                'ownerId' => $user->getId()
            ]);
            return self::indexAction();
        }

        return new Response('album/create_form');
    }

    public static function showAction() {
        $id = Util::getValue($_GET, 'id', null);

        if($id == null) return self::indexAction();

        $response = new Response('album/show');
        $album = AlbumDao::findByIdAndOwnerId($id);

        if($album != null)
        {
            $response->getTemplate()->assignAlpha('name', $album->getName());
            $response->getTemplate()->assignAlpha('images', $album->getImages());
        }

        $response->getTemplate()->assignAlpha('album', 'Vous n\'avez pas d\'albums !');
        return $response;
    }

    public static function addImageAction() {
        $image = Util::getValue($_POST, 'image', null);
        $album = Util::getValue($_POST, 'album', null);
        if($image == null || $album == null){
            return self::indexAction();
        }

        $lastIndex = AlbumDao::getLastIndex($album, $image);

        AlbumDao::insertImage($album, $image, $lastIndex+1);

        return ImageController::indexAction();
    }

    public static function addToAlbumAction() {
        $image = Util::getValue($_GET, 'image', null);

        $select = '';
        $userId = $_SESSION['user_id'];
        if(!empty($userId))
        {
            $albums = AlbumDao::findAllByOwnerId($userId);
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

        $response = new Response('album/choose_album');
        $response->getTemplate()->assignAlpha('select', $select);
        $response->getTemplate()->assignAlpha('image', $image);
        return $response;
    }
}