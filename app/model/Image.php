<?php

namespace App\model;

/**
 * Class Image
 * @package App\model
 *
 * Représente une image et ses informations.
 */
class Image {

    private $id;
    private $url;
    private $category;
    private $comment;

    function __construct($params = []) {
        if(!empty($params))
            $this
                ->setId($params['id'])
                ->setUrl($params['url'])
                ->setCategory($params['category'])
                ->setComment($params['comment']);
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
     * @return Image
     */
    private function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Image
     */
    private function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     * @return Image
     */
    private function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return Image
     */
    private function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }
}
