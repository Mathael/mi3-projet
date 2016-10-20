<?php
session_start();
/**
 * @author: LEBOC Philippe
 * Date: 07/10/2016
 * Time: 17:30
 */
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <title>Base de données d'images</title>
        <link href="assets/css/style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
<?php

    /////////////////////////////////////////
    /////       FRONT CONTROLLER        /////
    /////////////////////////////////////////

    // Affichage des erreurs
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL | E_STRICT);

    // Définitions de constantes
    define('PROJECT_DIR', realpath('./'));
    define('FRONT_CONTROLLER', 'Yes I\'m coming from front controller !');
    define('CONTROLLER_DIR', PROJECT_DIR.'/controllers/');
    define('MODEL_DIR', PROJECT_DIR.'/model/');
    define('DAO_DIR', PROJECT_DIR.'/dao/');
    define('UTIL_DIR', PROJECT_DIR.'/utils/');
    define('VIEW_DIR', PROJECT_DIR.'/view/');
    define('IMG_DIR', PROJECT_DIR.'/assets/images/jons');

    // Objects
    require_once MODEL_DIR.'ViewData.php';
    require_once MODEL_DIR.'Image.php';

    // Classes utilitaires
    require_once UTIL_DIR.'Util.php';

    // DAO
    require_once DAO_DIR.'Database.php';
    require_once DAO_DIR.'ImageDAO.php';

    // Interfaces
    require_once CONTROLLER_DIR.'DefaultController.php';

    // Controllers
    require_once CONTROLLER_DIR.'TestController.php';
    require_once CONTROLLER_DIR.'IndexController.php';
    require_once CONTROLLER_DIR.'ImageController.php';
    require_once CONTROLLER_DIR.'AboutController.php';

    // Vue constante sur: header
    require_once VIEW_DIR.'commons/header.html';

    // Récupère la page vers laquelle l'utilisaur souhaite se rendre.
    $page = empty($_GET['page']) ? null : ucfirst(htmlspecialchars($_GET['page'])).'Controller';

    // La page par défaut est l'index
    // - Cas où l'utilisateur n'a pas renseigné la page qu'il veut atteindre
    // - Cas où l'utilisateur vient tout juste d'arriver sur le site
    if($page == null || !class_exists($page)) {
        $page = 'IndexController';
    }

    // Récupère l'action demandée par l'utilisateur
    $action = empty($_GET['action']) ? null : htmlspecialchars($_GET['action']).'Action';

    // L'action par défaut est index
    // Il est obligatoire d'avoir une action par défaut pour au moins appeler la méthode par défaut du controller
    // La méthode par défaut d'un controller est: indexAction()
    if($action == null || !method_exists($page, $action)) {
        $action = 'indexAction';
    }

    $parameters = $_GET;
    unset($parameters['page']);
    unset($parameters['action']);

    call_user_func([$page, $action], $parameters);

    // Vue constante sur le footer qui se place juste avant la fin de la page
    require_once VIEW_DIR.'commons/footer.html';
?>
    </body>
</html>
