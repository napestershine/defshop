<?php

namespace App;

/**
 * Class Plugin
 * @package App
 */
class Plugin
{
    /**
     * @param null $file
     * @param null $data
     * @return null|string
     */
    public static function get($file = null, $data = null)
    {
        $path = PLUGIN_PATH . DS . $file . '.php';

        if (!empty($file) && is_file($path)) {
            ob_start();
            @include($path);
            return ob_get_clean();
        }
        return null;
    }

}
