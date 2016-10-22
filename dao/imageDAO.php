<?php

/**
 * Permet l'accès aux données et de les renvoyer sous forme d'instance
 * ou de tableau d'instances.
 *
 * Class ImageDAO
 */
final class ImageDAO {

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

    public static function getAll() {
        $stmt = Database::getInstance()->prepare('SELECT * FROM image');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, "Image");
    }

    public static function getFirstImage() {
        $image = NULL;
        $stmt = Database::getInstance()->prepare('SELECT * FROM image ORDER BY id ASC LIMIT 1');
        $stmt->execute();

        if($result = $stmt->fetch()) {
            $image = new Image($result);
        }
        return $image;
    }

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

    public static function getImageCount() {
        $stmt = Database::getInstance()->prepare('SELECT count(*) as cnt FROM image');
        $stmt->execute();

        $row = $stmt->fetch();
        return $row['cnt'];
    }

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
}