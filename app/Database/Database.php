<?php

namespace App\Database;

use \PDO;
use \PDOException;
use App\Helper;

/**
 * Class Database
 * @package App\Database
 */
abstract class Database
{
    /**
     * @var
     */
    protected $_schema;

    /**
     * @var
     */
    protected $_hostname;

    /**
     * @var
     */
    protected $_port;

    /**
     * @var
     */
    protected $_database;

    /**
     * @var
     */
    protected $_username;

    /**
     * @var
     */
    protected $_password;

    /**
     * @var bool
     */
    private $_persistent = true;

    /**
     * @var int
     */
    private $_fetchMode = PDO::FETCH_ASSOC;

    /**
     * @var array
     */
    private $_driverOptions = array();

    /**
     * @var null
     */
    private $_connectionString = null;

    /**
     * @var null
     */
    private $_pdoObject = null;

    /**
     * @var
     */
    public $affectedRows;

    /**
     * @var
     */
    public $id;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->_connect();
    }

    /**
     * @param null $e
     * @param null $message
     * @return string
     */
    private function _exceptionOutput($e = null, $message = null)
    {
        if (is_object($e)) {
            if (ENVIRONMENT == 1) {
                return $e->getMessage();
            } else {
                return '<p>' . $message . '</p>';
            }
        }
    }

    /**
     * @param null $key
     * @param null $value
     */
    public function setDriverOption($key = null, $value = null)
    {
        $this->_driverOptions[$key] = $value;
    }

    /**
     * Set Database Connection.
     */
    private function _setConnection()
    {
        switch ($this->_schema) {
            case 'mysql':
                $this->_connectionString = "mysql:dbname={$this->_database};host={$this->_hostname};port={$this->_port}";
                break;
            case 'sqlite':
                $this->_connectionString = "sqlite:{$this->_database}";
                break;
            case 'pgsql':
                $this->_connectionString = "pgsql:dbname={$this->_database};host={$this->_hostname}";
                break;
        }

        $this->setDriverOption(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8");
        $this->setDriverOption(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($this->_persistent) {
            $this->setDriverOption(PDO::ATTR_PERSISTENT, true);
        }
    }

    /**
     * Connect Database.
     */
    private function _connect()
    {
        $this->_setConnection();
        try {
            $this->_pdoObject = new PDO(
                $this->_connectionString,
                $this->_username,
                $this->_password,
                $this->_driverOptions
            );
        } catch (PDOException $e) {
            echo $this->_exceptionOutput($e, 'There was a problem with the Database connection');
        }
    }

    /**
     * @param null $sql
     * @param null $params
     * @return mixed
     */
    private function _query($sql = null, $params = null)
    {
        if (!empty($sql)) {
            if (!is_object($this->_pdoObject)) {
                $this->_connect();
            }

            $statement = $this->_pdoObject->prepare($sql, $this->_driverOptions);

            if (!$statement) {
                $errorInfo = $this->_pdoObject->errorInfo();
                throw new PDOException("Database error [{$errorInfo[0]}]: {$errorInfo[2]}, driver error code is {$errorInfo[1]}");
            }

            $paramsConverted = array();

            if (get_magic_quotes_gpc() === true) {
                if (is_array($params)) {
                    foreach ($params as $key => $value) {
                        $paramsConverted[$key] = stripcslashes($value);
                    }
                } else {
                    $paramsConverted[] = stripcslashes($params);
                }
            } else {
                $paramsConverted = is_array($params) ? $params : array($params);
            }

            if (!$statement->execute($paramsConverted) || $statement->errorCode() != '00000') {
                $errorInfo = $statement->errorInfo();
                throw new PDOException("Database error [{$errorInfo[0]}]: {$errorInfo[2]}, driver error code is {$errorInfo[1]}<br />SQL: {$sql}");
            }

            $this->affectedRows = $statement->rowCount();

            return $statement;
        }
    }

    /**
     * @param null $fetchMode
     */
    public function setFetchMode($fetchMode = null)
    {
        if (!empty($fetchMode)) {
            $this->_fetchMode = $fetchMode;
        }
    }

    /**
     * @param null $sequenceName
     * @return mixed
     */
    public function getLastInsertId($sequenceName = null)
    {
        return $this->_pdoObject->lastInsertId($sequenceName);
    }

    /**
     * @param null $sql
     * @param null $params
     * @return bool
     */
    public function fetchAll($sql = null, $params = null)
    {
        if (!empty($sql)) {
            try {
                $statement = $this->_query($sql, $params);
                return $statement->fetchAll($this->_fetchMode);
            } catch (PDOException $e) {
                echo $this->_exceptionOutput($e, 'Something went wrong trying to fetch records');
            }
        }
        return false;
    }

    /**
     * @param null $sql
     * @param null $params
     * @return bool
     */
    public function fetchOne($sql = null, $params = null)
    {
        if (!empty($sql)) {
            try {
                $statement = $this->_query($sql, $params);
                return $statement->fetch($this->_fetchMode);
            } catch (PDOException $e) {
                echo $this->_exceptionOutput($e, 'Something went wrong while fetching the record');
            }
        }

        return false;
    }

    /**
     * @param null $sql
     * @param null $params
     * @return bool|mixed
     */
    public function execute($sql = null, $params = null)
    {
        if (!empty($sql)) {
            try {
                return $this->_query($sql, $params);
            } catch (PDOException $e) {
                echo $this->_exceptionOutput($e, 'Something went wrong when executing the sql statement');
            }
        }

        return false;
    }

    /**
     * @param null $array
     * @param null $pre
     * @return array
     */
    private function _insertArray($array = null, $pre = null)
    {
        if (!empty($array) && is_array($array)) {
            $fields = array();
            $holders = array();
            $values = array();

            foreach ($array as $key => $value) {
                $fields[] = !empty($pre) ? "`{$pre}.{$key}`" : "`{$key}`";
                $holders[] = "?";
                $values[] = $value;
            }

            return array($fields, $holders, $values);
        }
    }

    /**
     * @param null $array
     * @param null $pre
     * @return array
     */
    private function _updateArray($array = null, $pre = null)
    {
        if (!empty($array) && is_array($array)) {
            $fields = array();
            $values = array();

            foreach ($array as $key => $value) {
                $fields[] = !empty($pre) ? "`{$pre}.{$key}` = ?" : "`{$key}` = ?";
                $values[] = $value;
            }

            return array($fields, $values);
        }
    }

    /**
     * @param null $table
     * @param null $array
     * @return bool
     */
    public function insert($table = null, $array = null)
    {
        $array = $this->_insertArray($array);

        if (!empty($table) && !empty($array)) {
            $sql = "INSERT INTO `{$table}` (";
            $sql .= implode(", ", $array[0]);
            $sql .= ") VALUES (";
            $sql .= implode(", ", $array[1]);
            $sql .= ")";

            $return = $this->execute($sql, $array[2]);

            if ($return) {
                $this->id = $this->getLastInsertId();
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * @param null $table
     * @param null $array
     * @param null $value
     * @param null $field
     * @return bool
     */
    private function _areUpdateParametersValid($table = null, $array = null, $value = null, $field = null)
    {
        return (!empty($table) && !empty($array) && !Helper::isEmpty($value) && !empty($field));
    }

    /**
     * @param null $table
     * @param null $array
     * @param null $value
     * @param string $field
     * @return bool|mixed
     */
    public function update($table = null, $array = null, $value = null, $field = 'id')
    {
        $array = $this->_updateArray($array);

        if ($this->_areUpdateParametersValid($table, $array, $value, $field)) {
            $sql = "UPDATE `{$table}` SET ";
            $sql .= implode(", ", $array[0]);
            $sql .= " WHERE `{$field}` = ?";
            $array[1][] = $value;

            return $this->execute($sql, $array[1]);
        }
        return false;
    }

    /**
     * @param null $table
     * @param null $value
     * @param null $field
     * @return bool
     */
    private function _areDeleteParametersValid($table = null, $value = null, $field = null)
    {
        return (!empty($table) && !Helper::isEmpty($value) && !empty($field));
    }

    /**
     * @param null $table
     * @param null $value
     * @param string $field
     * @return bool|mixed
     */
    public function delete($table = null, $value = null, $field = 'id')
    {
        if ($this->_areDeleteParametersValid($table, $value, $field)) {
            $sql = "DELETE FROM `{$table}`
                    WHERE `{$field}` = ?";

            return $this->execute($sql, $value);
        }
        return false;
    }

    /**
     * @param null $table
     * @param null $value
     * @param null $field
     * @return bool
     */
    private function _isSelectOneValid($table = null, $value = null, $field = null)
    {
        return (!empty($table) && !Helper::isEmpty($value) && !empty($field));
    }

    /**
     * @param null $table
     * @param null $value
     * @param string $field
     * @return bool|null
     */
    public function selectOne($table = null, $value = null, $field = 'id')
    {
        if ($this->_isSelectOneValid($table, $value, $field)) {
            $sql = "SELECT *
                    FROM `{$table}`
                    WHERE `{$field}` = ?";

            return $this->fetchOne($sql, $value);
        }
        return null;
    }

    /**
     * Begin Transaction.
     */
    public function beginTransaction()
    {
        if (!is_object($this->_pdoObject)) {
            $this->_connect();
        }

        $this->_pdoObject->beginTransaction();
    }

    /**
     * Commit to Database.
     */
    public function commit()
    {
        if (!is_object($this->_pdoObject)) {
            $this->_connect();
        }
        $this->_pdoObject->commit();
    }

    /**
     * Rollback Transaction.
     */
    public function rollBack()
    {
        if (!is_object($this->_pdoObject)) {
            $this->_connect();
        }
        $this->_pdoObject->rollBack();
    }

    /**
     * @param null $sql
     * @param null $params
     * @return bool|mixed
     */
    public function executeTransaction($sql = null, $params = null)
    {
        if (!empty($sql)) {
            return $this->_query($sql, $params);
        }
        return false;
    }

    /**
     * @param null $table
     * @param null $array
     * @return bool
     */
    public function insertTransaction($table = null, $array = null)
    {
        $array = $this->_insertArray($array);

        if (!empty($table) && !empty($array)) {
            $sql = "INSERT INTO `{$table}` (";
            $sql .= implode(", ", $array[0]);
            $sql .= ") VALUES (";
            $sql .= implode(", ", $array[1]);
            $sql .= ")";

            $return = $this->executeTransaction($sql, $array[2]);

            if ($return) {
                $this->id = $this->getLastInsertId();
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * @param null $table
     * @param null $array
     * @param null $value
     * @param string $field
     * @return bool|mixed
     */
    public function updateTransaction($table = null, $array = null, $value = null, $field = 'id')
    {
        $array = $this->_updateArray($array);

        if ($this->_areUpdateParametersValid($table, $array, $value, $field)) {
            $sql = "UPDATE `{$table}` SET ";
            $sql .= implode(", ", $array[0]);
            $sql .= " WHERE `{$field}` = ?";

            $array[1][] = $value;

            return $this->executeTransaction($sql, $array[1]);
        }
        return false;
    }

    /**
     * @param null $table
     * @param null $value
     * @param string $field
     * @return bool|mixed
     */
    public function deleteTransaction($table = null, $value = null, $field = 'id')
    {
        if ($this->_areDeleteParametersValid($table, $value, $field)) {
            $sql = "DELETE FROM `{$table}`
                    WHERE `{$field}` = ?";
            return $this->executeTransaction($sql, $value);
        }
        return false;
    }

    /**
     * @param null $sql
     * @param null $params
     * @return null
     */
    public function getOneTransaction($sql = null, $params = null)
    {
        if (!empty($sql)) {
            $statement = $this->_query($sql, $params);
            return $statement->fetch($this->_fetchMode);
        }
        return null;
    }

    /**
     * @param null $table
     * @param null $value
     * @param string $field
     * @return null
     */
    public function selectOneTransaction($table = null, $value = null, $field = 'id')
    {
        if ($this->_isSelectOneValid($table, $value, $field)) {
            $sql = "SELECT *
                    FROM `{$table}`
                    WHERE `{$field}` = ?";
            return $this->getOneTransaction($sql, $value);
        }
        return null;
    }

}
