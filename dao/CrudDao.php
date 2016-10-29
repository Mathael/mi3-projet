<?php

/**
 * @author LEBOC Philippe.
 * Date: 28/10/2016
 * Time: 20:30
 */
interface CrudDao
{
    public static function create($params);
    public static function findAll();
    public static function findById($id);
    public static function update($params);
    public static function delete($id);
}