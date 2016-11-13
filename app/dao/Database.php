<?php

namespace App\dao;

use App\config\Config;
use PDO;
use PDOException;

/**
 * @author LEBOC Philippe
 * Date: 07/10/2016
 * Time: 23:45
 * Sert à la gestion de la base de donnée, c'est une class Singleton.
 *
 * Utilisation :
 * $statement = Database::getInstance()->prepare("SELECT * FROM users WHERE id=? AND ville=? LIMIT ?");
 * les paramètres doivent être "binder" à la requête
 * Ensuite traiter le retour grace à while($resultat = $statement->fetch())
 */
final class Database {

    private static $_instance = null;

    public static function getInstance() {
        if(is_null(self::$_instance)) {
            try{
                //self::$_instance = new PDO('sqlite:database.db'); Cas d'utilisation d'une base de données sqlite
                self::$_instance = new PDO(Config::DATABASE_TYPE.':host='.Config::DATABASE_URL.';port='.Config::DATABASE_PORT.';dbname='.Config::DATABASE_NAME.';charset=UTF8', Config::DATABASE_USER, Config::DATABASE_PASSWORD);
                self::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            }catch(PDOException $e){
                die('Une erreur est survenue lors de l\'initialisation de la base de données : '.$e->getMessage());
            }
        }
        return self::$_instance;
    }

    public static function close() {
        if(self::$_instance != null) {
            self::$_instance = null;
        }
    }

    public static function install() {
        $files = ['create', 'insert'];

        foreach ($files as $file)
        {
            $request = '';
            $request = file_get_contents("sql/$file.sql");
            $request = str_replace("\n","",$request); // supprime les retours chariots
            $request = str_replace("\r","",$request); // supprime les retours chariots
            self::getInstance()->exec($request);
        }
    }
}