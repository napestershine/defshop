<?php

namespace App;

/**
 * Class Core
 * @package App
 */
class Core
{
    /**
     * @var Url
     */
    public $objUrl;

    /**
     * @var Navigation
     */
    public $objNavigation;

    /**
     * @var Currency
     */
    public $objCurrency;

    /**
     * @var
     */
    public $objAdmin;

    /**
     * @var string
     */
    public $meta_title = 'E-commerce project';

    /**
     * @var string
     */
    public $meta_description = 'E-commerce project';

    /**
     * Core constructor.
     */
    public function __construct()
    {
        $this->objUrl = new Url();
        $this->objNavigation = new Navigation($this->objUrl);
        $this->objCurrency = new Currency();
    }

    /**
     * Application Run.
     */
    public function run()
    {

        ob_start();

        switch ($this->objUrl->module) {

            case 'panel':
                set_include_path(implode(PATH_SEPARATOR, array(
                    realpath(ROOT_PATH . DS . 'admin' . DS . TEMPLATE_DIR),
                    realpath(ROOT_PATH . DS . 'admin' . DS . PAGES_DIR),
                    get_include_path()
                )));
                $this->objAdmin = new Admin();
                @require_once(ROOT_PATH . DS . 'admin' . DS . PAGES_DIR . DS . $this->objUrl->cpage . '.php');
                break;

            default:
                set_include_path(implode(PATH_SEPARATOR, array(
                    realpath(ROOT_PATH . DS . TEMPLATE_DIR),
                    realpath(ROOT_PATH . DS . PAGES_DIR),
                    get_include_path()
                )));

                @require_once(ROOT_PATH . DS . PAGES_DIR . DS . $this->objUrl->cpage . '.php');

        }

        ob_get_flush();

    }
}





