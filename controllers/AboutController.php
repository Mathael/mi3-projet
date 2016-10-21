<?php
if(!defined("FRONT_CONTROLLER"))
{
    throw new Exception();
}

class AboutController implements DefaultController {

    public static function indexAction() {
        require_once (VIEW_DIR.'commons/menu.html');
        require_once (VIEW_DIR.'about.html');
    }
}