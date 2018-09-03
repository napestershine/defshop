<?php

namespace App;

/**
 * Class Country
 * @package App
 */
class Country extends Application
{

    /**
     * @var string
     */
    protected $_table = 'countries';

    /**
     * @return bool
     */
    public function getCountries()
    {
        $sql = "SELECT *
				FROM `{$this->_table}`
				WHERE `include` = ?
				ORDER BY `name` ASC";
        return $this->_Db->fetchAll($sql, 1);
    }

    /**
     * @param null $id
     * @return bool|null
     */
    public function getCountry($id = null)
    {
        if (!empty($id)) {
            $sql = "SELECT *
					FROM `{$this->_table}`
					WHERE `id` = ?
					AND `include` = ?";
            return $this->_Db->fetchOne($sql, array($id, 1));
        }
        return null;
    }

    /**
     * @return bool
     */
    public function getAllExceptLocal()
    {
        $sql = "SELECT *
				FROM `{$this->_table}`
				WHERE `id` != ?
				ORDER BY `name` ASC";
        return $this->_Db->fetchAll($sql, COUNTRY_LOCAL);
    }

    /**
     * @return bool
     */
    public function getAll()
    {
        $sql = "SELECT *
				FROM `{$this->_table}`
				ORDER BY `name` ASC";
        return $this->_Db->fetchAll($sql);
    }

}
