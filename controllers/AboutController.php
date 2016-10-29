<?php
namespace App\controllers;

use App\utils\TemplateManager;

final class AboutController implements DefaultController {
    public static function indexAction() {
        $template = new TemplateManager('about');
        $template->show();
    }
}
