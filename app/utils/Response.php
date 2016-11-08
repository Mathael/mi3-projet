<?php
/**
 * @author LEBOC Philippe.
 * Date: 01/11/2016
 * Time: 17:34
 */

namespace App\utils;

/**
 * L'intéret de cette classe est d'effectuer les action entre le controller et le générateur de template
 * Un exemple concret serait un éventuel retour JSON. Cette conversion serait effectuée et le moteur de template HTML
 * ne serait pas appelé. Cependant le controller renverrais tout de même le même type d'objet : Un Response.
 *
 *
 * Class Response
 * @package App\utils
 */
class Response
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_BAD = 400;
    const HTTP_UNAUTHORISED = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_I_AM_A_TEA_POT = 418; // RFC 23245 datée du premier avril 1998, Hyper Text Coffee Pot Control Protocol.

    public function __construct($file)
    {
        global $template;

        $template->init($file);
        return $this;
    }

    public function getTemplate() {
        global $template;
        return $template;
    }

    public function toJson() {
        // TODO: implements me
    }
}