<?php

namespace App\utils;

use App\config\Config;
use App\model\User;
use ReflectionClass;
use ReflectionProperty;

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
    private $filename;
    private $directory;
    private $stream;

    /**
     * TemplateManager constructor.
     * Il est obligatoire d'appeler init($file)
     */
    function __construct() {}

    /**
     * ATTENTION : OBLIGATOIRE !
     *
     * @param $file string le fichier principal à charger.
     */
    public function init($file) {
        $path = explode('/', $file);
        $name = array_pop($path);
        $path = implode($path);

        $this
            ->setFilename($name)
            ->setDirectory($path)
            ->setStream($this->getContent($file));
    }

    // Ne supporte pas les tableaux de valeurs [1, 2, 3, 4, 5, 6] or ['key' => 1, ...]
    public function assign($key, $something) {
        $result = '';

        if(!is_array($something))
        {
            if(is_object($something)) {
                // Exemple : assignAlpha('image' => $image);
                $result = $this->assignObjectAlpha($something);
            } else {
                // Exemple : assignAlpha('id' => 123);
                $result = $something;
            }
        }
        else
        {
            // Exemple : assignAlpha([Image, Image, Image ...]);
            foreach ($something as $object) {
                $result .= $this->assignObjectAlpha($object);
            }
        }

        $this->assignKeyValue($key, $result);
    }

    /**
     * @param Object $object l'objet dont les attributs vont être liés au flux HTML
     * @return string le flux html de l'objet créé à partir d'un fichier html contenant "_small"
     */
    private function assignObjectAlpha($object)
    {
        global $user;
        $result = $user->getRole() == User::ROLE_ADMIN ? $this->getContent($this->getDirectory().'/'.$this->getFilename().'_small_admin') : $this->getContent($this->getDirectory().'/'.$this->getFilename().'_small');
        $result = $this->resolveObject($object, $result);
        return $result;
    }

    /**
     * TODO: description détaillée
     *
     * @param Object $object un objet ou un tableau d'objet
     * @param string $result une variable contenant du html avec des {{key}} non résolues
     * @param string $path une variable contenant le chemin actuelle des {{key}} en cas d'objets présents en attributs.
     * @return string $result avec ses {{key}} traduite par les attributs de $object
     */
    private function resolveObject($object, $result, $path = '')
    {
        // La réflection permet d'accéder aux informations d'une class.
        // Dans notre cas, on s'intéresse aux attributs privés (sans Reflection, il faudrait les passer en public)
        $reflect = new ReflectionClass($object);
        $props = $reflect->getProperties(ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE);

        // Pour chaque objet, accède à ses attributs pour repérer les clé valeurs à modifier dans le HTML
        foreach ($props as $attr)
        {
            $method = 'get'.ucfirst($attr->getName());

            // Gestion des atributs de type tableaux d'objets
            // Ex: $user->getName() ne retourne pas un tableau
            // Ex: $album->getImages() retourne un tableau
            if(is_array($object->$method()))
            {
                foreach ($object->$method() as $key => $elem)
                {
                    if(is_object($elem))
                    {
                        $classname = (new ReflectionClass($object))->getShortName();
                        $path .= $classname . '.';
                        return $this->resolveObject($elem, $result, $path);
                    } else {
                        $result = str_replace($key, $elem, $result);
                    }
                }
            }
            else
            {
                // Décommenter la ligne ci-dessous pour voir la convention de nommage.
                //echo $path.get_class($object).'.'.$attr->getName().'<br/>';
                $classname = (new ReflectionClass($object))->getShortName();
                $result = str_replace('{{'.$path.$classname.'.'.$attr->getName().'}}', $object->$method(), $result);
            }
        }

        return $result;
    }

    /**
     * Incorpore un fichier HTML dans le HTML courant (getFile()); à la place des {{$key}}
     * @param $key string
     * @param $file string
     */
    public function assignTemplate($key, $file) {
        if(empty($file)) {
            echo ('TemplateManager: Fichier HTML non présent');
        }

        $this->assignKeyValue($key, $this->getContent($file));
    }

    /**
     * Remplace chaque {{$key}} de la vue, par la valeur correspondante du tableau associatif
     * @param $array array
     */
    public function assignArray($array) {
        foreach ($array as $key => $value) {
            $this->assignKeyValue($key, $value);
        }
    }

    public function assignArrayTemplate($key, $template, $templateKey, $values) {
        $result = '';
        $template = $this->getContent($template);

        foreach ($values as $value) {
            $result .= $template;
            $result = str_replace('{{'.$templateKey.'}}', $value, $result);
        }

        $this->assignKeyValue($key, $result);
    }

    /**
     * @param $key string : chaine de caractères présente dans le HTML sous forme {{valeur}}
     * @param $value string : valeur réelle
     */
    private function assignKeyValue($key, $value) {
        if(empty($this->getStream())) {
            echo ('Le flux HTML est vide');
        }

        $this->setStream(str_replace('{{'.$key.'}}', $value, $this->getStream()));
    }

    /**
     * Effectue les affectations de variables automatiques (en fonction des configurations)
     *
     * {{app_url}}      = http://url/
     * {{app_assets}}   = http://url/assets/
     */
    public function cleanup() {
        $this->setStream(str_replace('{{app_url}}', Config::APP_URL, $this->getStream()));
        $this->setStream(str_replace('{{app_directory}}', Config::APP_DIRECTORY, $this->getStream()));
        $this->setStream(str_replace('{{app_assets}}', Config::APP_URL.Config::APP_DIRECTORY.DS.'assets'.DS, $this->getStream()));
    }

    /**
     * Ajoute le contenu d'une page HTML dans la variable $file
     * @param $html
     * @return $this
     */
    public function addTemplateFile($html) {
        $this->setStream($this->getStream() . $this->getContent($html));
        return $this;
    }

    /**
     * Affiche le contenu présent dans $file (flux HTML)
     */
    public function show() {
        $mainTemplate = $this->getContent('template');
        $this->setStream(str_replace('{{content}}', $this->getStream(), $mainTemplate));
        $this->setStream(str_replace('{{#require:commons/menu#}}', $this->getContent('commons/menu'), $this->getStream()));
        $this->setStream(str_replace('{{#require:commons/footer#}}', $this->getContent('commons/footer'), $this->getStream()));
        $this->buildMenu();

        $this->cleanup();
        echo $this->getStream();
    }

    /**
     * Construction du menu
     *
     * Le menu possède des boutons à gauche et à droite.
     * D'où les variables $left et $right
     */
    public function buildMenu() {
        global $user;
        $left = '';
        $right = '';

        if(!empty($_SESSION['authenticated'])) {
            $left .= '<li class="nav-item"><a class="nav-link" href="?page=album"><i class="fa fa-book" aria-hidden="true"></i> Albums</a></li>';
            if($user->getRole() == User::ROLE_ADMIN)
                $right .= '<li class="nav-item"><a class="nav-link" href="?page=admin"><i class="fa fa-cog" aria-hidden="true"></i> Administration</a></li>';
            $right .= '<li class="nav-item"><a class="nav-link" href="?page=session&action=logout"><i class="fa fa-power-off" aria-hidden="true"></i> Déconnexion ['.$user->getUsername().']</a></li>';
        } else {
            $right .= '<li class="nav-item"><a class="nav-link" href="?page=session"><i class="fa fa-power-off" aria-hidden="true"></i>  Connexion</a></li>';
            $right .= '<li class="nav-item"><a class="nav-link" href="?page=session&action=registerform">Inscription</a></li>';
        }

        $this->setStream(str_replace('{{menu_left}}', $left, $this->getStream()));
        $this->setStream(str_replace('{{menu_right}}', $right, $this->getStream()));
    }

    /**
     * Récupère le contenu d'un fichier HTML se trouvant dans le dossier: view
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
    private function getStream()
    {
        return $this->stream;
    }

    /**
     * @param string $file
     * @return TemplateManager
     */
    private function setStream($file)
    {
        $this->stream = $file;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return TemplateManager
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     * @return TemplateManager
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }
}