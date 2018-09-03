<?php

namespace App\Database\Extension;

use App\Database\Database;

/**
 * Class MySQL
 * @package App\Database\Extension
 */
class MySQL extends Database
{
    /**
     * @var string
     */
    protected $_schema = 'mysql';

    /**
     * @var string
     */
    protected $_hostname = DB_HOST;
    /**
     * @var string
     */
    protected $_port = DB_PORT;

    /**
     * @var string
     */
    protected $_database = DB_NAME;

    /**
     * @var string
     */
    protected $_username = DB_USER;

    /**
     * @var string
     */
    protected $_password = DB_PASS;

    /**
     * MySQL constructor.
     * @param array|null $array
     */
    public function __construct(array $array = null)
    {
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                $this->{$key} = $value;
            }
        }
        parent::__construct();
    }

}
