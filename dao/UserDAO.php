<?php

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

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
    }

    /**
     * @param $username
     * @return User|NULL
     */
    public static function getUserByUsername($username) {
        $stmt = Database::getInstance()->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->bindParam("username", $username);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
    }

    /**
     * @return array
     */
    public static function getAllUsers() {
        $stmt = Database::getInstance()->prepare("SELECT * FROM user");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
    }

    /**
     * @param $username
     * @param $password
     * @return array
     */
    public static function getUser($username, $password) {
        $stmt = Database::getInstance()->prepare("SELECT * FROM user WHERE username = :username AND password = :password");
        $stmt->bindParam("username", $username);
        $stmt->bindParam("password", $password);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
    }

    /**
     * Vérifie que l'utilisateur existe
     * @param $username
     * @return mixed
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
    public static function createUser($username, $password) {
        $stmt = Database::getInstance()->prepare("INSERT INTO user(username, password) VALUES(:username, :password)");
        $stmt->bindParam("username", $username);
        $stmt->bindParam("password", $password);
        $stmt->execute();

        return self::getUserByUsername($username);
    }
}