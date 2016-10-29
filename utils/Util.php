<?php

namespace App\utils;

/**
 * @author LEBOC Philippe.
 * Date: 20/10/2016
 * Time: 11:10
 */
class Util
{
    /**
     * Fonction permettant de vérifier qu'une valeur existe bien dans un tableau
     *  si oui : retourne la valeur présente dans le tableau
     *  si non : retourne la valeur par défaut
     * @param $tab
     * @param $key
     * @param $defaultValue
     * @return mixed
     */
    public static function getValue($tab, $key, $defaultValue) {
        if(array_key_exists($key, $tab))
            return $tab[$key];
        return $defaultValue;
    }
}