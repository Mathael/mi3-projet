
		<div id="menu">		
			<h3>Menu</h3>
			<ul>

					
					
					# Mise en place du menu
					$menu['Home']="index.php";
					$menu['A propos']="aPropos.php";
					// Pre-calcule la première image
					$newImg = $imgDAO->getFirstImage();     
					# Change l'etat pour indiquer que cette image est la nouvelle
					$newImgId=$newImg->getId(); 
					$menu['First']="viewPhoto.php?imgId=$newImgId&size=$size";
					# Pre-calcule une image au hasard
					$menu['Random']="nonRealise.php";
					# Pour afficher plus d'image passe à une autre page
					$menu['More']="viewPhotoMatrix.php?imgId=$imgId";    
					// Demande à calculer un zoom sur l'image
					$menu['Zoom +']="zoom.php?zoom=1.25&imgId=$imgId&size=$size";
					// Demande à calculer un zoom sur l'image
					$menu['Zoom -']="nonRealise.php"; 
					// Affichage du menu
					foreach ($menu as $item => $act) {
						print "<li><a href=\"$act\">$item</a></li>\n";
					}
					?>
				</ul>
			</div>
		




