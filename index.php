<?php
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
    define('VIEW_DIR', PROJECT_DIR.'/view/');
    define('IMG_DIR', PROJECT_DIR.'/assets/images/jons');

    // Object
    require_once MODEL_DIR.'Image.class.php';

    // DAO
    require_once DAO_DIR.'Database.class.php';
    require_once DAO_DIR.'ImageDAO.php';

    // Vue constante sur: header
    require_once VIEW_DIR.'commons/header.html';

    // Récupère la page vers laquelle l'utilisaur souhaite se rendre.
    $page = empty($_GET['page']) ? 'index' : htmlspecialchars($_GET['page']); // TODO: check difference between EMPTY and ISSET

    // Routing de la page vers le bon controller
    switch($page)
    {
        case 'pictures': require_once CONTROLLER_DIR.'ImageController.php'; break;
        case 'about': require_once CONTROLLER_DIR.'AboutController.php'; break;
        default: require_once CONTROLLER_DIR.'DefaultController.php';
    }

    // Vue constante sur le footer qui se place juste avant la fin de la page
    require_once VIEW_DIR.'commons/footer.html';

?>
    </body>
</html>
