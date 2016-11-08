<?php

namespace App\controllers;
use App\dao\UserDAO;
use App\model\User;
use App\utils\Response;
use App\utils\Util;

/**
 * @author LEBOC Philippe
 * Date: 20/10/2016
 * Time: 10:56
 *
 * Gestion de session utilisateur
 *  - connexion
 *  - déconnexion
 *  - ...
 */
final class SessionController implements DefaultController
{
    /**
     * Affichage de la page de connexion
     */
    public static function indexAction() {
        return new Response('sessions/login');
    }

    /**
     * Connecte l'utilisateur (session)
     */
    public static function loginAction() {
        global $user;
        if($user->getRole() != User::ROLE_ANONYMOUS) {
            return IndexController::indexAction();
        }

        $username = Util::getValue($_POST, 'username', null);
        $password = Util::getValue($_POST, 'password', null);

        // Vérification des champs
        if(($username == null || empty($username)) || ($password == null || empty($password))) {
            return IndexController::indexAction(); // TODO: error page
        }

        // Récupération de l'utilisateur correspondant et vérifie qu'il existe
        $dbuser = UserDAO::getUser($username, $password);
        if($dbuser == null) {
            return IndexController::indexAction(); // TODO: error page
        }

        // Remet à zéro la session anonyme pour démarrer une session utilisateur
        session_reset();
        $_SESSION = [];

        // Actuellement, seul l'admin se connecte donc tous les comptes sont admin.
        $_SESSION['authenticated'] = true;
        $_SESSION['user_role'] = $dbuser->getRole();
        $_SESSION['user_username'] = $dbuser->getUsername();
        $_SESSION['user_id'] = $dbuser->getId();

        global $user;
        $user = $dbuser;

        // Redirige vers la page d'index
        // TODO: notifier l'utilisateur qu'il est bien connecté
        return IndexController::indexAction();
    }

    /**
     * Déconnecte l'utilisateur (session)
     */
    public static function logoutAction(){
        if(!empty($_SESSION['authenticated'])) {
            global $user;
            $user = new User([
                'id' => -1,
                'username' => 'Anonymous',
                'password' => '',
                'role' => 0
            ]);
            $_SESSION = [];
            session_destroy();
        }

        return IndexController::indexAction();
    }

    /**
     * Affiche le formulaire d'inscription
     * @return Response
     */
    public static function registerformAction() {
        global $user;
        if($user->getRole() != User::ROLE_ANONYMOUS) {
            return IndexController::indexAction();
        }

        return new Response('sessions/register');
    }

    /**
     * Crée un utilisateur et l'insert en base de donnée puis connecte immédiatement le nouvel utilisateur
     */
    public static function registerAction() {
        global $user;
        if($user->getRole() != User::ROLE_ANONYMOUS) {
            return IndexController::indexAction();
        }

        $username = Util::getValue($_POST, 'username', null);
        $password = Util::getValue($_POST, 'password', null);
        $password2nd = Util::getValue($_POST, 'password2nd', null);

        // Vérification des champs
        if(($username == null || empty($username)) ||
            ($password == null || empty($password)) ||
            ($password2nd == null || empty($password2nd))) {
            return IndexController::indexAction(); // TODO: error page
        }

        // Récupération de l'utilisateur correspondant et vérifie qu'il existe
        $usernameExists = UserDAO::userExists($username);
        if($usernameExists) {
            return IndexController::indexAction(); // TODO: error page
        }

        // Crée l'utilisateur
        $dbuser = UserDAO::create([
            'username' => $username,
            'password' => $password,
            'role' => User::ROLE_USER
        ]);

        if($dbuser == null) {
            return IndexController::indexAction(); // TODO: error page
        }

        session_reset();
        $_SESSION['authenticated'] = true;
        $_SESSION['user_role'] = $dbuser->getRole();
        $_SESSION['user_username'] = $dbuser->getUsername();
        $_SESSION['user_id'] = $dbuser->getId();

        global $user;
        $user = $dbuser;

        // Retour à l'index
        return IndexController::indexAction();
    }
}