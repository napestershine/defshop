<?php

namespace App;

/**
 * Class Navigation
 * @package App
 */
class Navigation
{

    /**
     * @var Url|null
     */
    public $objUrl;

    /**
     * @var string
     */
    public $classActive = 'act';

    /**
     * Navigation constructor.
     * @param null $objUrl
     */
    public function __construct($objUrl = null)
    {
        $this->objUrl = is_object($objUrl) ? $objUrl : new Url();
    }

    /**
     * @param null $main
     * @param null $pairs
     * @param bool $single
     * @return string
     */
    public function active($main = null, $pairs = null, $single = true)
    {
        if (!empty($main)) {
            if (empty($pairs)) {
                if ($main == $this->objUrl->main) {
                    return !$single ?
                        ' ' . $this->classActive :
                        ' class="' . $this->classActive . '"';
                }
            } else {
                $exceptions = array();
                foreach ($pairs as $key => $value) {
                    $paramUrl = $this->objUrl->get($key);
                    if ($paramUrl != $value) {
                        $exceptions[] = $key;
                    }
                }
                if ($main == $this->objUrl->main && empty($exceptions)) {
                    return !$single ?
                        ' ' . $this->classActive :
                        ' class="' . $this->classActive . '"';
                }
            }
        }
    }


}
