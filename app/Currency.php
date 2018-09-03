<?php

namespace App;

use \Exception;

/**
 * Class Currency
 * @package App
 */
class Currency
{

    /**
     * @var array
     */
    private $_list = array(

        'GBP' => array(
            'symbol' => '£',
            'html' => '&#163;'
        ),
        'USD' => array(
            'symbol' => '$',
            'html' => '&#36;'
        ),
        'EUR' => array(
            'symbol' => '€',
            'html' => '&#8364;'
        )

    );

    /**
     * @var string
     */
    private $_default = 'USD';

    /**
     * @var
     */
    private $_index;

    /**
     * @var
     */
    public $code;

    /**
     * @var
     */
    public $symbol;

    /**
     * @var
     */
    public $html;

    /**
     * Currency constructor.
     */
    public function __construct()
    {
        $this->_process();
    }

    /**
     * @param null $value
     * @return string
     */
    public function display($value = null)
    {

        switch ($this->_index) {

            case 'GBP':
                return $this->symbol . $value;
                break;
            case 'USD':
                return $this->symbol . $value;
                break;
            case 'EUR':
                return $this->symbol . $value;
                break;

        }

    }

    /**
     * @param null $code
     * @return bool
     */
    private function _codeExists($code = null)
    {
        return (!empty($code) && array_key_exists($code, $this->_list));
    }

    /**
     *
     */
    private function _getCurrentIndex()
    {

        try {
            $this->_index = $this->_default;

            if (!$this->_codeExists($this->_index)) {
                throw new Exception('The currency code could not be found');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }

    }

    /**
     *
     */
    private function _process()
    {
        $this->_getCurrentIndex();
        $this->code = $this->_index;
        $this->symbol = $this->_list[$this->_index]['symbol'];
        $this->html = $this->_list[$this->_index]['html'];
    }

}
