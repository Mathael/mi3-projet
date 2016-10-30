<?php

namespace App\model;

/**
 * @author LEBOC Philippe.
 * Date: 28/10/2016
 * Time: 20:23
 */
class Album
{
    private $id;
    private $name;
    private $createDate;
    private $images;
    private $owner;

    public function __construct($params = []) {
        if($params != null)
        {
            $this
                ->setId($params['id'])
                ->setName($params['name'])
                ->setCreateDate($params['createDate'])
                ->setOwner($params['owner'])
                ->setImages([]);
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Album
     */
    private function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Album
     */
    private function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param int $createDate
     * @return Album
     */
    private function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
        return $this;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param array $images
     * @return Album
     */
    public function setImages($images)
    {
        $this->images = $images;
        return $this;
    }

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     * @return Album
     */
    private function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }
}