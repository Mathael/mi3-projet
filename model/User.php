<?php
/**
 * @author LEBOC Philippe.
 * Date: 20/10/2016
 * Time: 10:44
 *
 * Cette classe permet la représentation modèle d'un utilisateur connecté (session) au site.
 */
class User
{
    public static $ROLE_ADMIN = 'ADMIN';
    public static $ROLE_USER = 'USER';

    private $id;
    private $username;
    private $password;

    function __construct($params = []) {
        if(!empty($params))
            $this
                ->setId($params['id'])
                ->setUsername($params['username'])
                ->setPassword($params['password']);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    private function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return User
     */
    private function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return User
     */
    private function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
}