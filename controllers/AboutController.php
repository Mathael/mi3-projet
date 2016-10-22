<?php
if(!defined("FRONT_CONTROLLER"))
{
    throw new Exception();
}

final class AboutController implements DefaultController {
    public static function indexAction() {
        $template = new TemplateManager('');
        $template->addTemplateFile('about');
        $template->show();
    }
}
