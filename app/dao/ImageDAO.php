<?php

namespace App\dao;

use App\model\Image;
use PDO;

/**
 * Permet l'accès aux données et de les renvoyer sous forme d'instance
 * ou de tableau d'instances.
 *
 * @author Lucas Georges
 * Class ImageDAO
 */
final class ImageDAO implements CrudDao {

    //private static $QUERY_SELECT_FIRST_IMAGE = 'SELECT i.*, avg(s.stars) as stars FROM image i INNER JOIN image_stars s ON i.id = s.imageId GROUP BY s.imageId ORDER BY i.id ASC LIMIT 1';
    private static $QUERY_SELECT_IMAGE_STARS = 'SELECT avg(stars) as stars FROM image_stars WHERE imageId = :id GROUP BY imageId';

    /**
     * Retourne un objet Image en fonction de son id
     * @param $id integer
     * @return Image|null
     */
    public static function findById($id) {
        $image = NULL;
        $stmt = Database::getInstance()->prepare('SELECT * FROM image WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();

        if($result = $stmt->fetch()) {
            $image = new Image($result);
            $stars = self::findStarsByImageId($image->getId());
            $image->setStars($stars);
        }
        return $image;
    }

    /**
     * Retourne une liste d'objets Image en fonction d'un id de départ et d'un nombre maximum
     * @param $id integer
     * @param $count integer
     * @return array
     */
    public static function getImageList($id, $count) {
        $stmt = Database::getInstance()->prepare('SELECT * FROM image WHERE id >= :id AND id <= :nb');
        $stmt->bindValue('id', $id);
        $stmt->bindValue('nb', max(1, $id+$count-1));
        $stmt->execute();

        /** @var Image[] $images */
        $images = $stmt->fetchAll(PDO::FETCH_CLASS, Image::class);

        foreach($images as $image) {
            $stars = self::findStarsByImageId($image->getId());
            $image->setStars($stars);
        }
        return $images;
    }

    /**
     * Retourne la première image de la table (ordonnée par l'id)
     * @return Image|null
     */
    public static function getFirstImage() {
        $image = NULL;
        $stmt = Database::getInstance()->prepare('SELECT * FROM image ORDER BY id ASC LIMIT 1');
        $stmt->execute();

        if($result = $stmt->fetch()) {
            $image = new Image($result);
            $stars = self::findStarsByImageId($image->getId());
            $image->setStars($stars);
        }
        return $image;
    }

    /**
     * Retourne l'image précédant celle ayant l'id passé en paramètre
     * @param $id
     * @return Image|NULL
     * @internal param Image $image
     */
    public static function getPrevImage($id) {
        $res = NULL;
        $stmt = Database::getInstance()->prepare('SELECT * FROM image WHERE id=:id');
        $stmt->bindValue('id', max(1, $id-1));
        $stmt->execute();

        $image = null;
        if($result = $stmt->fetch()) {
            $image = new Image($result);
            $stars = self::findStarsByImageId($image->getId());
            $image->setStars($stars);
        }
        return $image;
    }

    /**
     * Retourne l'image suivant celle ayant l'id passé en paramètre
     * @param $id
     * @return Image|null
     * @internal param int $image l'id de l'image
     */
    public static function getNextImage($id) {
        $res = NULL;
        $stmt = Database::getInstance()->prepare('SELECT * FROM image WHERE id=:id');
        $stmt->bindValue('id', $id+1);
        $stmt->execute();

        $image = null;
        if($result = $stmt->fetch()) {
            $image = new Image($result);
            $stars = self::findStarsByImageId($image->getId());
            $image->setStars($stars);

        }
        return $image;
    }

    /**
     * Retourne le nombre d'images présentes dans la table image
     * @return Integer
     */
    public static function size() {
        $stmt = Database::getInstance()->prepare('SELECT count(*) as cnt FROM image');
        $stmt->execute();

        $row = $stmt->fetch();
        return $row['cnt'];
    }

    /**
     * Retourne une image choisie aléatoirement
     * @return Image|null
     */
    public static function getRandomImage() {
        $count = self::size();
        $stmt = Database::getInstance()->prepare('SELECT * FROM image WHERE id=:id');
        $stmt->bindValue('id', rand(1, $count));
        $stmt->execute();

        $image = null;

        if($result = $stmt->fetch()) {
            $image = new Image($result);
            $stars = self::findStarsByImageId($image->getId());
            $image->setStars($stars);
        }
        return $image;
    }

    /**
     * Retourne une liste d'images choisie aléatoirement
     * @param $displayCnt integer
     * @return array
     */
    public static function getRandomImageList($displayCnt) {
        if($displayCnt <= 0 || !is_numeric($displayCnt)) {
            die('Le nombre d images doit être un entier supérieur à zero');
        }

        $ids = [];
        $max = self::size();
        for($i = 0; $i < $displayCnt ; $i++) {
            $ids[] = rand(1, $max);
        }

        $statement = 'SELECT * FROM image WHERE';
        foreach ($ids as $id) {
            if($id == $ids[0])
                $statement .= ' id='.$id;
            else
                $statement .= ' OR id='.$id;
        }

        $stmt = Database::getInstance()->prepare($statement);
        $stmt->execute();

        /** @var Image[] $images */
        $images = $stmt->fetchAll(PDO::FETCH_CLASS, Image::class);

        foreach($images as $image) {
            $stars = self::findStarsByImageId($image->getId());
            $image->setStars($stars);
        }

        return $images;
    }

    /**
     * @return string[] l'ensemble des catégories d'images présentes dans la bdd
     */
    public static function getCategories() {
        $stmt = Database::getInstance()->prepare('SELECT category FROM image GROUP BY category');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN, 'category');
    }

    /**
     * @param $id int l'identifiant correspondant à l'image qu'on veut modifier
     * @param $comment string la nouvelle valeur (ou l'ancienne si non modifiée)
     * @param $category string la nouvelle catégorie (ou l'ancienne si non modifiée)
     * @return bool true si succès de la requête, false sinon
     */
    public static function edit($id, $comment, $category) {
        $stmt = Database::getInstance()->prepare('UPDATE image SET comment = :comment, category = :category WHERE id=:id');
        $stmt->bindValue('comment', $comment);
        $stmt->bindValue('category', $category);
        $stmt->bindValue('id', $id);
        return $stmt->execute();
    }

    /**
     * @param $id int l'identifiant de l'image à modifier
     * @return bool
     */
    public static function delete($id) {
        $image = NULL;
        $stmt = Database::getInstance()->prepare('DELETE FROM image WHERE id=:id');
        $stmt->bindValue('id', $id);
        return $stmt->execute();
    }

    /**
     * @param $params []
     * @return bool
     */
    public static function create($params) {
        $stmt = Database::getInstance()->prepare('INSERT INTO image(url, category, comment) VALUES (:url, :category, :comment)');
        $stmt->bindValue('url', $params['url']);
        $stmt->bindValue('category', $params['category']);
        $stmt->bindValue('comment', $params['comment']);
        return $stmt->execute();
    }

    /**
     * @return Image[]
     */
    public static function findAll()
    {
        /** @var Image[] $images */
        $stmt = Database::getInstance()->prepare('SELECT * FROM image');
        $stmt->execute();
        $images = $stmt->fetchAll(PDO::FETCH_CLASS, Image::class);

        foreach($images as $image) {
            $stars = self::findStarsByImageId($image->getId());
            $image->setStars($stars);
        }

        return $images;
    }

    /**
     * @param $id int l'identifiant de l'image pour laquelle on veut récupérer la moyenne des vote
     * @return int la moyenne des votes attribuée à l'image d'identifiant $id
     */
    public static function findStarsByImageId($id) {
        $stmt = Database::getInstance()->prepare(self::$QUERY_SELECT_IMAGE_STARS);
        $stmt->bindValue('id', $id);
        $stmt->execute();

        $cnt = 0;
        if($result = $stmt->fetch()) {
            $cnt = $result['stars'] != null ? $result['stars'] : 0;
        }
        return $cnt;
    }

    /**
     * @param $imgId int l'identifiant de l'image pour laquelle le vote est effectué
     * @param $vote int nombre d'étoiles attribuées
     * @return bool success / fail
     */
    public static function proceedVote($imgId, $vote) {
        global $user;
        $stmt = Database::getInstance()->prepare('INSERT IGNORE image_stars VALUES(:userId, :imageId, :stars)');
        $stmt->bindValue('imageId', $imgId);
        $stmt->bindValue('userId', $user->getId());
        $stmt->bindValue('stars', $vote);
        return $stmt->execute(); // changer l'INSERT IGNORE et catch l'erreur retournée
    }

    public static function getImageByCategorie($category){
        $table = [];
        $stmt = Database::getInstance()->prepare('SELECT * FROM image WHERE category = :category');
        $stmt->bindValue('category', $category);
        $stmt->execute();

        while($result = $stmt->fetch()) {
            $table[] = new Image($result);
        }
        return $table;
    }
}