<?php
if(!defined("FRONT_CONTROLER"))
{
    throw new Exception();
}
/**
 * @author LEBOC Philippe
 * Date: 07/10/2016
 * Time: 23:45
 * Sert à la gestion de la base de donnée, c'est une class Singleton.
 *
 * Utilisation :
 * $statement = Database::getInstance()->prepare("SELECT * FROM users WHERE id=? AND ville=? LIMIT ?");
 * les paramètres doivent être "binder" à la requête
 * Ensuite traité le retour grace à while($resultat = $statement->fetch())
 */
class Database {

    private static $_instance = null;

    public static function getInstance(){
        if(is_null(self::$_instance)){
            try{
                self::$_instance = new PDO('sqlite:database.db');
                self::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            }catch(PDOException $e){
                die('Une erreur est survenue lors de l\'initialisation de la base de données : '.$e->getMessage());
            }
        }
        return self::$_instance;
    }
}