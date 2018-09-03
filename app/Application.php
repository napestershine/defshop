<?php

namespace App;

use App\Database\Extension\MySQL;

/**
 * Class Application
 * @package App
 */
abstract class Application
{
    /**
     * @var MySQL
     */
    protected $_Db;

    /**
     * @var
     */
    protected $_table;

    /**
     * @var
     */
    public $id;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->_Db = new MySQL();
    }

    /**
     * @param null $id
     * @param string $field
     * @return bool|null
     */
    public function getOne($id = null, $field = 'id')
    {
        return $this->_Db->selectOne($this->_table, $id, $field);
    }

    /**
     * @param null $array
     * @return bool
     */
    public function insert($array = null)
    {
        if ($this->_Db->insert($this->_table, $array)) {
            $this->id = $this->_Db->id;
            return true;
        }
        return false;
    }

    /**
     * @param null $array
     * @param null $id
     * @return bool|mixed
     */
    public function update($array = null, $id = null)
    {
        return $this->_Db->update($this->_table, $array, $id);
    }

    /**
     * @param null $id
     * @return bool|mixed
     */
    public function delete($id = null)
    {
        return $this->_Db->delete($this->_table, $id);
    }

}
