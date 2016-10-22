<?php

/**
 * @author LEBOC Philippe
 * Date: 22/10/2016
 * Time: 21:50
 *
 * Cette classe à pour objectif de retirer le code PHP de nos templates et
 * de mettre en place une réelle séparation.
 */
class TemplateManager
{
    private $file;

    /**
     * TemplateManager constructor.
     * @param $file string : file name from the VIEW DIRECTORY
     */
    function __construct($file) {
        $this->setFile($this->getContent($file));
    }

    /**
     * @param $key string : chaine de caractères présente dans le HTML sous forme {{valeur}}
     * @param $value string : valeur réelle
     */
    public function assign($key, $value) {
        if(empty($this->getFile())) {
            echo ('Fichier HTML non non présent dans le TemplateManager');
        }

        $this->setFile(str_replace('{{'.$key.'}}', $value, $this->getFile()));
    }

    /**
     * Incorpore un fichier HTML dans le HTML courant (getFile()); à la place des {{$key}}
     * @param $key string
     * @param $file string
     */
    public function assignTemplate($key, $file) {
        if(empty($file)) {
            echo ('TemplateManager: Fichier HTML non non présent');
        }

        $this->assign($key, $this->getContent($file));
    }

    /**
     * Remplace chaque {{$key}} de la vue, par la valeur correspondante du tableau associatif
     * @param $array array
     */
    public function assignArray($array) {
        foreach ($array as $key => $value) {
            $this->assign($key, $value);
        }
    }

    /**
     * @param $key string
     * @param $templateName string
     * @param $objects array
     */
    public function assignArrayObjects($key, $templateName, $objects) {
        $result = '';
        $template = $this->getContent($templateName);

        // Boucle sur chaque objet de la liste d'objets à traiter
        foreach ($objects as $object) {
            $result .= $template;

            // La réflection permet d'accéder aux attributs privé (sinon il faudrait les passer en public)
            $reflect = new ReflectionClass($object);
            $props = $reflect->getProperties(ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE);

            // Pour chaque objet, accède à ses attributs pour repérer les clé valeurs à modifier dans le HTML
            foreach ($props as $attr) {
                $method = 'get'.ucfirst($attr->getName());
                $result = str_replace('{{'.get_class($object).'.'.$attr->getName().'}}', $object->$method(), $result);
            }
        }

        //var_dump($result);
        $this->assign($key, $result);
    }

    /**
     * Add HTML file to the current template
     * @param $html
     * @return $this
     */
    public function addTemplateFile($html) {
        $this->setFile($this->getFile() . $this->getContent($html));
        return $this;
    }

    /**
     * Affiche le contenu présent dans le TemplateManager
     */
    public function show() {
        echo $this->getFile();
    }

    /**
     * Récupère le contenu d'un fichier HTML
     * @param $file string : le fichier HTML
     * @return string
     */
    private function getContent($file) {
        if(empty($file)) return '';
        return file_get_contents(VIEW_DIR.$file.'.html');
    }

    /**
     * @return string
     */
    private function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return TemplateManager
     */
    private function setFile($file)
    {
        $this->file = $file;
        return $this;
    }
}