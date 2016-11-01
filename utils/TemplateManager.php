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
     * Si le paramètre n'est pas donné,
     * @param $file string : file name from the VIEW DIRECTORY
     */
    function __construct($file) {
        $path = explode('/', $file);
        $name = array_pop($path);
        $path = implode($path);

        $this
            ->setFilename($name)
            ->setDirectory($path)
            ->setStream($this->getContent($file));
    }

    ////////////////////////////////////////////////

    // Ne supporte pas les tableaux de valeurs [1, 2, 3, 4, 5, 6] or ['key' => 1, ...]
    public function assignAlpha($key, $something) {
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

        $this->assign($key, $result);
    }

    private function assignObjectAlpha($object)
    {
        $result = $this->getContent($this->getDirectory().'/'.$this->getFilename().'_small');
        $result = $this->resolveObject($object, $result);
        return $result;
    }

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

    ////////////////////////////////////////////////

    /**
     * @param $key string : chaine de caractères présente dans le HTML sous forme {{valeur}}
     * @param $value string : valeur réelle
     */
    public function assign($key, $value) {
        if(empty($this->getStream())) {
            echo ('Fichier HTML non non présent dans le TemplateManager');
        }

        $this->setStream(str_replace('{{'.$key.'}}', $value, $this->getStream()));
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

    public function assignArrayTemplate($key, $template, $templateKey, $values) {
        $result = '';
        $template = $this->getContent($template);

        foreach ($values as $value) {
            $result .= $template;
            $result = str_replace('{{'.$templateKey.'}}', $value, $result);
        }

        $this->assign($key, $result);
    }

    public function assignObject($object) {
        // La réflection permet d'accéder aux attributs privé (sinon il faudrait les passer en public)
        $reflect = new ReflectionClass($object);
        $props = $reflect->getProperties(ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE);

        // Pour chaque objet, accède à ses attributs pour repérer les clé valeurs à modifier dans le HTML
        foreach ($props as $attr) {
            $method = 'get'.ucfirst($attr->getName());
            $classname = (new ReflectionClass($object))->getShortName();
            $this->setStream(str_replace('{{'.$classname.'.'.$attr->getName().'}}', $object->$method(), $this->getStream()));
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
                $classname = (new ReflectionClass($object))->getShortName();
                $result = str_replace('{{'.$classname.'.'.$attr->getName().'}}', $object->$method(), $result);
            }
        }

        $this->assign($key, $result);
    }

    /**
     * Effectue les affectations de variables automatiques (en fonction des configurations)
     *
     * {{app_url}}      = http://url/
     * {{app_assets}}   = http://url/assets/
     */
    public function cleanup() {
        $this->setStream(str_replace('{{app_url}}', Config::APP_URL, $this->getStream()));
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
            $left .= '<li class="nav-item"><a class="nav-link" href="?page=album">Albums</a></li>';
            if($user->getRole() == User::ROLE_ADMIN)
                $right .= '<li class="nav-item"><a class="nav-link" href="?page=admin"><i class="fa fa-cog" aria-hidden="true"></i> Administration</a></li>';
            $right .= '<li class="nav-item"><a class="nav-link" href="?page=session&action=logout">Déconnexion ['.$user->getUsername().']</a></li>';
        } else {
            $right .= '<li class="nav-item"><a class="nav-link" href="?page=session">Connexion</a></li>';
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