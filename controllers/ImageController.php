<?php
    if(!defined("FRONT_CONTROLLER"))
    {
        throw new Exception();
    }

    // Valeur par défaut
    $size = 480;

    // Recupère les éventuels paramètres
    $displayImgCnt = isset($_GET['displaycount']) && is_numeric($_GET['displaycount']) ? $_GET['displaycount'] : NULL;
    $zoom = isset($_GET['zoom']) && is_numeric($_GET['zoom']) ? $zoom = $_GET['zoom'] : 1;

    // Récupère l'ID de l'image demandée, sinon recupère l'ID de la première image
    $img = isset($_GET['imgId']) && is_numeric($_GET['imgId']) ? ImageDAO::getImage($_GET['imgId']) : ImageDAO::getFirstImage();
    $imgId = $img->getId();

    if ($zoom != 1) $size *= $zoom;

    // Crée les variables nécessaires à la vue [image.html]
    $prevImgId = ImageDAO::getPrevImage($img)->getId();
    $nextImgId = ImageDAO::getNextImage($img)->getId();
    $imgUrl = $img->getUrl();

    $menu['First'] = '?page=pictures&imgId='.ImageDAO::getFirstImage()->getId();
    $menu['Random'] = '?page=pictures&imgId='.ImageDAO::getRandomImage()->getId();
    $menu['More'] = '?page=pictures&action=more&imgId=' . $imgId;
    $menu['less'] = '?page=pictures&action=less&imgId=' . $imgId;
    $menu['Zoom +'] = '?page=pictures&zoom=' . ($zoom + 0.25) . '&imgId=' . $imgId . '&size=' . $size;
    $menu['Zoom -'] = '?page=pictures&zoom=' . ($zoom - 0.25) . '&imgId=' . $imgId . '&size=' . $size;

    require_once(VIEW_DIR . 'commons/menu.html');
    require_once(VIEW_DIR . 'image.html');