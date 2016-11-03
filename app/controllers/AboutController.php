<?php
namespace App\controllers;

use App\utils\Response;

final class AboutController implements DefaultController {
    public static function indexAction() {
        return new Response('about');
    }
}
