<?php

	class ImageDAO
    {
        public static function getSize(){
            $statement = Database::getInstance()->prepare("SELECT count(*) as cnt FROM image");
            $result = $statement->execute();

            return $result['cnt'];
        }

        public static function getById($id){
            $statement = Database::getInstance()->prepare("SELECT * FROM image WHERE id=:idImg");
            $statement->bindParam('idImg',$id);
            $statement->execute();
            $image=NULL;

            if($result=$statement->fetch()){
                var_dump($result);
                $image=new image($result['path'],$result['id']);
            }
            return $image;
        }

        public static function getAllImages(){
            $statement = Database::getInstance()->prepare("SELECT * FROM image");
            $statement->execute();
            $table = [];

            while($result = $statement->fetch()){
                $table[] = new image($result['path'],$result['id']);
            }
            return $table;
        }

        public static function getRandom(){
            $rand = rand( 1 ,getSize());
            $statement = Database::getInstance()->prepare("SELECT * FROM image WHERE id=:randID");
            $statement->bindParam(randID,$rand);
            $statement->execute();
            $image=NULL;

            if($result = $statement->fetch()){
                $image = new image($result['path'],$result['id']);
            }

            return $image;
        }

        public static function getFirst(){

            $statement = Database::getInstance()->prepare("SELECT * FROM image ORDER BY id ASC LIMIT 1");
            $statement->execute();
            $image=NULL;

            if($result = $statement->fetch()){
                $image = new image($result['path'],$result['id']);
            }

            return $image;
        }

        public static function getLast(){

            $statement = Database::getInstance()->prepare("SELECT * FROM image ORDER BY id DESC LIMIT 1");
            $statement->execute();
            $image=NULL;

            if($result = $statement->fetch()){
                $image = new image($result['path'],$result['id']);
            }
            return $image;
        }

        public static function getNextImg($id){
            if($id < self::getSize()){
                return self::getFirst();
            }
            $image = self::getById($id + 1);

            return $image;
        }
    }

?>