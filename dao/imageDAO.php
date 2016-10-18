<?php

	class ImageDAO
    {
        public static function getSize(){
            $statement = Database::getInstance()->prepare("SELECT count(*) as cnt FROM images");
            $result = $statement->execute();

            return $result['cnt'];
        }

        public static function getById($id){
            $statement = Database::getInstance()->prepare("SELECT * FROM images WHERE id=:idImg");
            $statement->bindParam('idImg',$id);
            $statement->execute();
            $image=NULL;

            if($result=$statement->fetch()){
                var_dump($result);
                $image=new image($result['url'],$result['id']);
            }
            return $image;
        }

        public static function getAllImages(){
            $statement = Database::getInstance()->prepare("SELECT * FROM images");
            $statement->execute();
            $table = [];

            while($result = $statement->fetch()){
                $table[] = new image($result['url'],$result['id']);
            }
            return $table;
        }

        public static function getRandom(){
            $rand = rand( 1 ,getSize());
            $statement = Database::getInstance()->prepare("SELECT * FROM images WHERE id=:randID");
            $statement->bindParam(randID,$rand);
            $statement->execute();
            $image=NULL;

            if($result = $statement->fetch()){
                $image = new image($result['url'],$result['id']);
            }

            return $image;
        }

        public static function getFirst(){

            $statement = Database::getInstance()->prepare("SELECT * FROM images ORDER BY id ASC LIMIT 1");
            $statement->execute();
            $image=NULL;

            if($result = $statement->fetch()){
                $image = new image($result['url'],$result['id']);
            }

            return $image;
        }

        public static function getLast(){

            $statement = Database::getInstance()->prepare("SELECT * FROM images ORDER BY id DESC LIMIT 1");
            $statement->execute();
            $image=NULL;

            if($result = $statement->fetch()){
                $image = new image($result['url'],$result['id']);
            }
            return $image;
        }
    }

?>