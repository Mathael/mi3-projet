<?php

/**
 * @author LEBOC Philippe.
 * Date: 28/10/2016
 * Time: 20:37
 */
class AlbumDao
{
    /**
     * @param array $params
     * @return bool
     */
    public static function create($params)
    {
        $name = Util::getValue($params, 'name', null);
        $ownerId = Util::getValue($params, 'ownerId', null);

        if($name == null | $ownerId == null) {
            return false;
        }

        $stmt = Database::getInstance()->prepare('INSERT INTO album(name, createDate, owner) VALUES(:name, NOW(), :owner)');
        $stmt->bindValue('name', $name);
        $stmt->bindValue('owner', $ownerId);
        return $stmt->execute();
    }

    /**
     * @param int $album
     * @param int $image
     * @param int $index
     * @return bool
     */
    public static function insertImage($album, $image, $index) {

        $stmt = Database::getInstance()->prepare('INSERT INTO album_images(album, image, image_index) VALUES(:album, :image, :index)');
        $stmt->bindValue('album', $album);
        $stmt->bindValue('image', $image);
        $stmt->bindValue('index', $index);
        return $stmt->execute();
    }

    /**
     * @return Album[]|null
     */
    public static function findAll()
    {
        /** @var Album[] $albums */

        $stmt = Database::getInstance()->prepare('SELECT * FROM album');
        $stmt->execute();

        $albums = $stmt->fetchAll(PDO::FETCH_CLASS, 'Album');

        if($albums == null || empty($albums)) return null;


        foreach ($albums as $album)
        {
            $images = self::getAlbumImages($album->getId());
            if($images != null) $album->setImages($images);
        }

        return $albums;
    }

    /**
     * @param int $id
     * @return Album|null
     */
    public static function findById($id)
    {
        $album = NULL;

        $stmt = Database::getInstance()->prepare('SELECT * FROM album WHERE id = :id');
        $stmt->bindValue('id', $id);
        $stmt->execute();

        if($res = $stmt->fetch()) {
            $album = new Album($res);
        }

        if($album == null) return null;

        $images = self::getAlbumImages($album->getId());

        if($images != null)
            $album->setImages($images);

        return $album;
    }

    public static function update($params)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function delete($id)
    {
        $stmt = Database::getInstance()->prepare('DELETE FROM album WHERE id = :id');
        $stmt->bindValue('id', $id);
        return $stmt->execute();
    }

    /**
     * @param int $albumId
     * @return Image[]
     */
    private static function getAlbumImages($albumId)
    {
        $stmt = Database::getInstance()->prepare('SELECT i.id, i.url, i.category, i.comment FROM image i, album_images a WHERE a.album = :album AND a.image = i.id');
        $stmt->bindValue('album', $albumId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Image');
    }
}