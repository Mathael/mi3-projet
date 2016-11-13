<?php
/**
 * @author LEBOC Philippe.
 * Date: 13/11/2016
 * Time: 14:06
 */

namespace App\controllers;

use App\dao\Database;
use App\utils\Response;

final class InstallController implements DefaultController
{
    /**
     * Installe la base de données
     */
    public static function indexAction()
    {
        Database::install();
        return new Response('admin/install');
    }
}