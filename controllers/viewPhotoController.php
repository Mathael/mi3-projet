<?php

    // Débute l'acces aux images
    $imgDAO = new ImageDAO();

    // Construit l'image courante
    // et l'ID courant
    // NB un id peut être toute chaine de caractère !!
    if (isset($_GET["imgId"])) {
        $imgId = $_GET["imgId"];
        $img = $imgDAO->getImage($imgId);
    } else {
        // Pas d'image, se positionne sur la première
        $img = $imgDAO->getFirstImage();
        // Conserve son id pour définir l'état de l'interface
        $imgId = $img->getId();
    }

    // Regarde si une taille pour l'image est connue
    if (isset($_GET["size"])) {
        $size = $_GET["size"];
    } else {
        # sinon place une valeur de taille par défaut
        $size = 480;
    }

require_once VIEW_DIR.'viewPhoto.html';