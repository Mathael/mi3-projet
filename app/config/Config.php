<?php
/**
 * @author LEBOC Philippe.
 * Date: 31/10/2016
 * Time: 19:38
 */

namespace App\config;


class Config
{
    // Configurations relatives à la base de données
    const DATABASE_URL = 'localhost';
    const DATABASE_TYPE = 'mysql';
    const DATABASE_PORT = '3306';
    const DATABASE_NAME = 'php-image-project';
    const DATABASE_USER = 'root';
    const DATABASE_PASSWORD = '';

    // Url du site : localhost par défaut
    const APP_URL = 'http://localhost/';

    // Nom du répertoire direct après 'www' sans ajouter de slash ou d'antislash
    // Si le projet possède 'www' pour racine, alors laisser cette variable vide : ''
    const APP_DIRECTORY = 'tp1';
}