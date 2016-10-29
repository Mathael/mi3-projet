<?php

namespace App\model;

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
    private $role;

    function __construct($params = []) {
        if(!empty($params))
            $this
                ->setId($params['id'])
                ->setUsername($params['username'])
                ->setPassword($params['password'])
                ->setRole($params['role']);
    }

    /**
     * @return string
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
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    private function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    private function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param $role string
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }


}