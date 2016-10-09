<?php
    if(!defined("FRONT_CONTROLLER"))
    {
        throw new Exception();
    }

    // Valeur par défaut
    $size = 480;

    // Recupère les éventuels paramètres
    $displayImgCnt = isset($_GET['displaycount']) && is_numeric($_GET['displaycount']) ? $_GET['displaycount'] : 1;
    $zoom = isset($_GET['zoom']) && is_numeric($_GET['zoom']) ? $zoom = $_GET['zoom'] : 1;

    // Récupère l'ID de l'image demandée, sinon recupère l'ID de la première image
    $img = NULL;
    isset($_GET['imgId']) && is_numeric($_GET['imgId']) ? $img = ImageDAO::getImageList($_GET['imgId'], $displayImgCnt) : $img[] = ImageDAO::getFirstImage();
    $imgId = $img[0]->getId();

    if ($zoom != 1) $size *= $zoom;

    // Crée les variables nécessaires à la vue [image.html]
    $prevImgId = ImageDAO::getPrevImage($img[0])->getId();
    $nextImgId = ImageDAO::getNextImage($img[0])->getId();
    $totalImageCount = ImageDAO::getImageCount();

    // Gestion du menu
    $menu['First'] = '?page=pictures&imgId=' . ImageDAO::getFirstImage()->getId();
    $menu['Random'] = '?page=pictures&imgId=' . ImageDAO::getRandomImage()->getId();
    $menu['More'] = '?page=pictures&imgId=' . $imgId . '&displaycount=' . min(($displayImgCnt * 2), $totalImageCount);
    $menu['less'] = '?page=pictures&imgId=' . $imgId . '&displaycount=' . max(1, $displayImgCnt / 2);
    $menu['Zoom +'] = '?page=pictures&zoom=' . ($zoom + 0.25) . '&imgId=' . $imgId;
    $menu['Zoom -'] = '?page=pictures&zoom=' . ($zoom - 0.25) . '&imgId=' . $imgId;

    require_once(VIEW_DIR . 'commons/menu.html');
    require_once(VIEW_DIR . 'image.html');