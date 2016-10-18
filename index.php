<!DOCTYPE html>
<html lang="fr" >
<head>
    <title>Site SIL3</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="assets/css/style.css" media="screen" title="Normal" />
</head>
<body>
<?php

    // Affichage des erreurs
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL | E_STRICT);

    // Définitions de constantes
    define('PROJECT_DIR', realpath('./')); // définis réprt courant projet
    define('FRONT_CONTROLER', 'Yes I\'m coming from front controllers !');
    define('CONTROLLER_DIR', PROJECT_DIR.'/controllers/');
    define('MODEL_DIR', PROJECT_DIR.'/model/');
    define('DAO_DIR', PROJECT_DIR.'/dao/');
    define('VIEW_DIR', PROJECT_DIR.'/view/');
    define('IMG_DIR', PROJECT_DIR.'/assets/images/jons');

    // Object
    require_once MODEL_DIR.'image.php';

    // DAO
    require_once DAO_DIR.'Database.class.php'; // singleton -> pour une instance de connexion avec bdd
    require_once DAO_DIR.'imageDAO.php';

    // Vue constante sur: header + nev
    require_once VIEW_DIR.'commons/header.html';
    require_once VIEW_DIR.'commons/nav.html';


    $page = empty($_GET['page']) ? NULL : htmlspecialchars($_GET['page']); // implémentation d'un TERNAIRE -> Permet de verifier si GET §NULL et renseigne une valeur dans le cas contraire
    switch($page)
    {
        case 'image':require_once CONTROLLER_DIR.'viewPhotoController.php'; break;
        case 'about':require_once CONTROLLER_DIR.'aboutController.php'; break;
        default: require_once CONTROLLER_DIR.'indexController.php'; break;
    }

    // Vue constante sur: footer
    require_once VIEW_DIR.'commons/footer.html';


?>
</body>
</html>