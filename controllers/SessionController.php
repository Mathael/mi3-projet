<?php

namespace App\controllers;
use App\dao\UserDAO;
use App\model\User;
use App\utils\TemplateManager;
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
        $template = new TemplateManager('sessions/login');
        $template->show();
    }

    /**
     * Connecte l'utilisateur (session)
     */
    public static function loginAction() {
        $username = Util::getValue($_POST, 'username', null);
        $password = Util::getValue($_POST, 'password', null);

        // Vérification des champs
        if(($username == null || empty($username)) || ($password == null || empty($password))) {
            return; // TODO: error page
        }

        // Récupération de l'utilisateur correspondant et vérifie qu'il existe
        $dbuser = UserDAO::getUser($username, $password);
        if($dbuser == null) {
            return; // TODO: error page
        }

        // Remet à zéro la session anonyme pour démarrer une session utilisateur
        session_reset();

        // Actuellement, seul l'admin se connecte donc tous les comptes sont admin.
        $_SESSION['authenticated'] = true;
        $_SESSION['user_role'] = $dbuser->getRole();
        $_SESSION['user_username'] = $dbuser->getUsername();
        $_SESSION['user_id'] = $dbuser->getId();

        // Redirige vers la page d'index
        // TODO: notifier l'utilisateur qu'il est bien connecté
        IndexController::indexAction();
    }

    /**
     * Déconnecte l'utilisateur (session)
     */
    public static function logoutAction(){
        if(empty($_SESSION['ROLE'])) {
            // TODO: error -> Vous n'êtes pas connecté
        }

        session_destroy();
        IndexController::indexAction();
    }

    public static function registerformAction() {
        $template = new TemplateManager('sessions/register');
        $template->show();
    }

    /**
     * Crée un utilisateur et l'insert en base de donnée puis connecte immédiatement le nouvel utilisateur
     */
    public static function registerAction() {
        $username = Util::getValue($_POST, 'username', null);
        $password = Util::getValue($_POST, 'password', null);
        $password2nd = Util::getValue($_POST, 'password2nd', null);

        // Vérification des champs
        if(($username == null || empty($username)) ||
            ($password == null || empty($password)) ||
            ($password2nd == null || empty($password2nd))) {
            echo 'field wrong';
            return; // TODO: error page
        }

        // Récupération de l'utilisateur correspondant et vérifie qu'il existe
        $usernameExists = UserDAO::userExists($username);
        if($usernameExists) {
            echo 'username exist';
            return; // TODO: error page
        }

        // Crée l'utilisateur
        $user = UserDAO::create([
            'username' => $username,
            'password' => $password,
            'role' => User::ROLE_USER
        ]);

        if($user == null) {
            echo 'user null';
            return; // TODO: error page
        }

        session_reset();
        $_SESSION['authenticated'] = true;
        $_SESSION['user_role'] = $user->getRole();
        $_SESSION['user_username'] = $user->getUsername();
        $_SESSION['user_id'] = $user->getId();

        // Retour à l'index
        IndexController::indexAction();
    }
}