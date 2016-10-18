<?php

class ViewData {

    private $images = [];
    private $size;

    public function ViewData($size = 480) {
        $this->setSize($size);
    }

    public function addImage(Image $image) {
        $this->images[] = $image;
    }

    /**
     * @return array
     */
    public function getImages(){
        return $this->images;
    }

    /**
     * @param array $images
     * @return ViewData
     */
    public function setImages($images)
    {
        $this->images = $images;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     * @return ViewData
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }
}