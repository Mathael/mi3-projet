<?php

namespace App;

use App\utils\Autoloader;

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
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/bootstrap-flex.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="container">
<?php

    /////////////////////////////////////////
    /////       FRONT CONTROLLER        /////
    /////////////////////////////////////////

    // Affichage des erreurs
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL | E_STRICT);

    // Définitions de constantes
    define('DS', DIRECTORY_SEPARATOR); // Séparateur de fichier définit pas l'installation PHP donc variera en fonction de l'OS installé.
    define('PROJECT_DIR', dirname(__FILE__).DS);
    define('CONTROLLER_DIR', PROJECT_DIR.'controllers'.DS);
    define('MODEL_DIR', PROJECT_DIR.'model'.DS);
    define('DAO_DIR', PROJECT_DIR.'dao'.DS);
    define('UTIL_DIR', PROJECT_DIR.'utils'.DS);
    define('VIEW_DIR', PROJECT_DIR.'view'.DS);
    define('IMG_DIR', PROJECT_DIR.'assets'.DS.'images'.DS.'jons');

    require UTIL_DIR.'Autoloader.php';
    Autoloader::register();

    // Vue constante sur: menu
    require_once VIEW_DIR.'commons/menu.html';

    // Récupère la page vers laquelle l'utilisaur souhaite se rendre.
    $page = empty($_GET['page']) ? null : ucfirst(strtolower(htmlspecialchars($_GET['page']))).'Controller';
    $controller =  'App\\controllers\\'.$page;

    // La page par défaut est l'index
    // - Cas où l'utilisateur n'a pas renseigné la page qu'il veut atteindre
    // - Cas où l'utilisateur vient tout juste d'arriver sur le site
    if($page == null || !file_exists(CONTROLLER_DIR.$page.'.php') || !class_exists($controller)) {
        $controller = 'App\\controllers\\IndexController';
    }

    // Récupère l'action demandée par l'utilisateur
    $action = empty($_GET['action']) ? null : htmlspecialchars($_GET['action']).'Action';

    // L'action par défaut est index
    // Il est obligatoire d'avoir une action par défaut pour au moins appeler la méthode par défaut du controller
    // La méthode par défaut d'un controller est: indexAction()
    if($action == null || !method_exists($controller, $action)) {
        $action = 'indexAction';
    }

    // function PHP permettant de lancer un appel de fonction d'une class.
    // en paramètre :
    // - Tableau contenant la classe et la fonction à executer
    // - Second paramètre optionnel : les paramètres de la fonction à executer
    call_user_func([$controller, $action]);

    // Vue constante sur le footer qui se place juste avant la fin de la page
    require_once VIEW_DIR.'commons/footer.html';
?>
        </div>
        <script src="assets/js/jquery-3.1.1.min.js" type="text/javascript"></script>
        <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/js/default.js" type="text/javascript"></script>
    </body>
</html>
