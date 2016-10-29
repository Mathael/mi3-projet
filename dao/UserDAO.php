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
final class UserDAO
{
    /**
     * Récupère un utilisateur depuis son id
     * @param $id
     * @return User|NULL
     */
    public static function getUserById($id) {
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
     * Récupère tout les utilisateurs
     * @return array
     */
    public static function getAllUsers() {
        $stmt = Database::getInstance()->prepare("SELECT * FROM user");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
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
     * Crée un utilisateur et le renvoie
     * @param $username
     * @param $password
     * @return User|NULL
     */
    public static function createUser($username, $password, $role) {
        $stmt = Database::getInstance()->prepare("INSERT INTO user(username, password, role) VALUES(:username, :password, :role)");
        $stmt->bindParam("username", $username);
        $stmt->bindParam("password", $password);
        $stmt->bindParam("role", $role);
        $stmt->execute();

        return self::getUserByUsername($username);
    }
}