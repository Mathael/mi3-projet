<?php

namespace App\model;

/**
 * @author LEBOC Philippe.
 * Date: 20/10/2016
 * Time: 10:44
 *
 * Class User
 * @package App\model
 *
 * Représente un utilisateur connecté (session) au site.
 */
class User
{
    const ROLE_ANONYMOUS = 0;
    const ROLE_USER = 1;
    const ROLE_ADMIN = 2;

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
     * @return int
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param $role int
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }


}