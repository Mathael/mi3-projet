<?php
if(!defined("FRONT_CONTROLLER"))
{
    throw new Exception();
}

final class IndexController implements DefaultController {
    public static function indexAction() {
        require_once VIEW_DIR.'index.html';
    }
}