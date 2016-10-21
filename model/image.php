<?php
if(!defined("FRONT_CONTROLLER"))
{
    throw new Exception();
}
class Image {

    private $id;
    private $url;
    private $category;
    private $comment;

    function __construct($params) {
        $this
            ->setId($params['id'])
            ->setUrl($params['path'])
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
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     * @return Image
     */
    private function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     * @return Image
     */
    private function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }
}
