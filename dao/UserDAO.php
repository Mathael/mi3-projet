<?php

namespace App\dao;

use App\model\User;
use PDO;

/**
 * @author LEBOC Philipe
 * Date: 20/10/2016
 * Time: 11:22
 *
 * Permet l'accès aux données et de les renvoyer sous forme d'instance
 * ou de tableau d'instances.
 */
final class UserDAO implements CrudDao
{
    /**
     * Récupère un utilisateur par son nom d'utilisateur
     * @param $username string
     * @return User|NULL
     */
    public static function getUserByUsername($username) {
        $stmt = Database::getInstance()->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->bindParam("username", $username);
        $stmt->execute();

        $user = null;

        if($res = $stmt->fetch()) {
            $user = new User($res);
        }

        return $user;
    }

    /**
     * Recupère un utilisateur en fonction de son nom d'utilisateur et de son mot de passe
     * @param $username string
     * @param $password string
     * @return User|NULL
     */
    public static function getUser($username, $password) {
        $stmt = Database::getInstance()->prepare("SELECT * FROM user WHERE username = :username AND password = :password");
        $stmt->bindParam("username", $username);
        $stmt->bindParam("password", $password);
        $stmt->execute();
        $user = null;

        if($res = $stmt->fetch()) {
            $user = new User($res);
        }

        return $user;
    }

    /**
     * Vérifie que l'utilisateur existe
     * @param $username
     * @return boolean
     */
    public static function userExists($username) {
        $stmt = Database::getInstance()->prepare("SELECT count(*) as cnt FROM user WHERE username = :username");
        $stmt->bindParam("username", $username);
        $stmt->execute();

        $count = $stmt->fetch()['cnt'];

        return $count > 0;
    }

    /**
     * Crée un utilisateur
     * @param $params []
     * @return User|NULL
     */
    public static function create($params)
    {
        $stmt = Database::getInstance()->prepare("INSERT INTO user(username, password, role) VALUES(:username, :password, :role)");
        $stmt->bindParam("username", $params['username']);
        $stmt->bindParam("password", $params['password']);
        $stmt->bindParam("role", $params['role']);
        $stmt->execute();

        return self::getUserByUsername($params['username']);
    }

    /**
     * Récupère l'ensemble des utilisateurs
     * @return User[]
     */
    public static function findAll()
    {
        $stmt = Database::getInstance()->prepare("SELECT * FROM user");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
    }

    /**
     * Récupère un utilisateur par son id
     * @param $id int
     * @return User|null
     */
    public static function findById($id)
    {
        $stmt = Database::getInstance()->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->bindParam("id", $id);
        $stmt->execute();

        $user = null;

        if($res = $stmt->fetch()) {
            $user = new User($res);
        }

        return $user;
    }

    /**
     * Récupère le nombre d'utilisateur présent dans la base de données
     * @return int
     */
    public static function size()
    {
        $stmt = Database::getInstance()->prepare('SELECT count(*) as cnt FROM user');
        $stmt->execute();

        $row = $stmt->fetch();
        return $row['cnt'];
    }

    /**
     * Supprime un utilisateur en fonction de son id
     * @param $id int
     * @return bool
     */
    public static function delete($id)
    {
        $stmt = Database::getInstance()->prepare("DELETE FROM user WHERE id = :id");
        $stmt->bindParam("id", $id);
        return $stmt->execute();
    }
}