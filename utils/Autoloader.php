<?php

namespace App\utils;

/**
 * @author LEBOC Philippe.
 * Date: 28/10/2016
 * Time: 22:52
 */
class Autoloader
{
    /**
     * Enregistre notre autoloader
     */
    static function register(){
        spl_autoload_extensions(".php");
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Inclue le fichier correspondant à notre classe
     * @param $class string Le nom de la classe à charger
     */
    static function autoload($class){
        // Les commentaires prennent en compte l'exemple de $class suivant : App\model\User

        // Explose notre variable $class par \
        // Avec notre exemple : $parts = ['App', 'model', 'User'];
        $parts = preg_split('#\\\#', $class);

        // Supprime App qui est le namespace de mon application mais ne correspond pas à l'arborescence du projet
        // Voir le namespace définit dans le FRONT CONTROLLER
        array_shift($parts);

        // Extrait le dernier element (qui est le nom de la Class)
        // Exemple: App\Model\[User]        Ici on récupère User
        $className = array_pop($parts);

        // On créé le chemin vers la classe
        // implode va remettre notre tableau sous forme de chaine en ajoutant un séparateur.
        $path = implode(DS, $parts);
        $file = $className.'.php';

        $filepath = PROJECT_DIR.strtolower($path).DS.$file;

        require $filepath;
    }
}