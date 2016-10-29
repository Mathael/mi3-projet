<?php

namespace App\dao;

use App\model\Image;
use PDO;

/**
 * Permet l'accès aux données et de les renvoyer sous forme d'instance
 * ou de tableau d'instances.
 *
 * Class ImageDAO
 */
final class ImageDAO {

    /**
     * Retourne un objet Image en fonction de son id
     * @param $id integer
     * @return Image|null
     */
    public static function getImage($id) {
        $image = NULL;
        $stmt = Database::getInstance()->prepare('SELECT * FROM image WHERE id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();

        if($result = $stmt->fetch()) {
            $image = new Image($result);
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
        $stmt->bindValue('nb', $id+$count-1);
        $stmt->execute();

        $res = [];
        while($result = $stmt->fetch()) {
            $res[] = new Image($result);
        }
        return $res;
    }

    /**
     * Retourne un tableau d'objets Image correspondant à l'ensemble des lignes (row) de la table image
     * @return array
     */
    public static function getAll() {
        $stmt = Database::getInstance()->prepare('SELECT * FROM image');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, "Image");
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
        }
        return $image;
    }

    /**
     * Retourne l'image précédant celle passée en paramètre
     * @param Image $image
     * @return Image
     */
    public static function getPrevImage(Image $image) {
        $res = NULL;
        $stmt = Database::getInstance()->prepare('SELECT * FROM image WHERE id=:id');
        $stmt->bindValue('id', max(1, $image->getId()-1));
        $stmt->execute();

        if($result = $stmt->fetch()) {
            $image = new Image($result);
        }
        return $image;
    }

    /**
     * Retourne l'image suivant celle passée en paramètre
     * @param Image $image
     * @return Image|null
     */
    public static function getNextImage(Image $image) {
        $res = NULL;
        $stmt = Database::getInstance()->prepare('SELECT * FROM image WHERE id=:id');
        $stmt->bindValue('id', $image->getId()+1);
        $stmt->execute();

        if($result = $stmt->fetch()) {
            $res = new Image($result);
        }
        return $res;
    }

    /**
     * Retourne le nombre d'images présentes dans la table image
     * @return Integer
     */
    public static function getImageCount() {
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
        $count = self::getImageCount();
        $stmt = Database::getInstance()->prepare('SELECT * FROM image WHERE id=:id');
        $stmt->bindValue('id', rand(1, $count));
        $stmt->execute();

        $image = null;

        if($result = $stmt->fetch()) {
            $image = new Image($result);
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
        $max = self::getImageCount();
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

        return $stmt->fetchAll(PDO::FETCH_CLASS, "Image");
    }

    public static function getCategories() {
        $stmt = Database::getInstance()->prepare('SELECT category FROM image GROUP BY category');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN, 'category');
    }

    /**
     * @param $id
     * @param $comment
     * @param $category
     * @return bool
     */
    public static function edit($id, $comment, $category) {
        $stmt = Database::getInstance()->prepare('UPDATE image SET comment = :comment, category = :category WHERE id=:id');
        $stmt->bindValue('comment', $comment);
        $stmt->bindValue('category', $category);
        $stmt->bindValue('id', $id);
        return $stmt->execute();
    }

    /**
     * @param $id
     * @return bool
     */
    public static function delete($id) {
        $image = NULL;
        $stmt = Database::getInstance()->prepare('DELETE FROM image WHERE id=:id');
        $stmt->bindValue('id', $id);
        return $stmt->execute();
    }

    /**
     * @param $url
     * @param $category
     * @param $comment
     * @return bool
     */
    public static function create($url, $category, $comment) {
        $stmt = Database::getInstance()->prepare('INSERT INTO image(url, category, comment) VALUES (:url, :category, :comment)');
        $stmt->bindValue('url', $url);
        $stmt->bindValue('category', $category);
        $stmt->bindValue('comment', $comment);
        return $stmt->execute();
    }
}