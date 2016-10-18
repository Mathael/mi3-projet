<?php

// Verifie qu'on est bien passer par le FRONT_CONTROLLER
if (empty(FRONT_CONTROLER)){
    throw new Exception();
}

class Database {
    private static $instance = NULL;

    public static function getInstance(){
        if(is_null(self::$instance)){
            try{
                self::$instance= new PDO('mysql:host=localhost;port=3307;dbname=images','root',''); // pensez à importer le fichier sql dans MySql (create.sql & inster.sql)
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            }
            catch (Exception $e){
                die("Erreur de connexion à la base de donnée");
            }
        }
        return self::$instance;
    }
}

