<?php
	// Débute l'acces aux images
	$imageDAO = new ImageDAO();

	// Récupère l'ID de l'image demandée, sinon recupère l'ID de la première image
	$img = isset($_GET['imgId']) && is_numeric($_GET['imgId']) ? $imageDAO->getImage($_GET['imgId']) : $imageDAO->getFirstImage();
	$imgId = $img->getId();

	// Regarde si une taille pour l'image est connue
	$size = isset($_GET["size"]) && is_integer($_GET["size"]) ? $size = $_GET['size'] : 480;
    $zoom = isset($_GET['zoom']) && is_numeric($_GET['zoom']) ? $zoom = $_GET['zoom'] : 1;

    if($zoom != 1) $size *= $zoom;

	// Crée les variables nécessaires à la vue [image.html]
	$firstImgId = $imageDAO->getFirstImage()->getId();
	$prevImgId = $imageDAO->getPrevImage($img)->getId();
	$nextImgId = $imageDAO->getNextImage($img)->getId();
	$imgUrl = $img->getURL();

	$menu['First']='?action=pictures&imgId='.$firstImgId.'&size=$size';
	$menu['Random']='?action=pictures&imgId='.$imageDAO->getRandomImage();
	$menu['More']='?action=pictures&imgId='.$imgId;
	$menu['Zoom +']='?action=pictures&zoom='.($zoom+0.25).'&imgId='.$imgId.'&size='.$size;
	$menu['Zoom -']='?action=pictures&zoom='.($zoom-0.25).'&imgId='.$imgId.'&size='.$size;

	require_once(VIEW_DIR.'commons/menu.html');
	require_once(VIEW_DIR.'image.html');