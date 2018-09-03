<?php

namespace App;

/**
 * Class CustomException
 * @package App
 */
class CustomException extends \Exception
{
    /**
     * @return bool
     */
    private static function _isDevelopment()
    {
        return (ENVIRONMENT === 1);
    }

    /**
     * @param null $e
     */
    public static function getOutput($e = null)
    {
        if (\is_object($e) && ($e instanceof \Exception)) {
            if (self::_isDevelopment()) {
                $out = array();
                $out[] = 'Message: ' . $e->getMessage();
                $out[] = 'File: ' . $e->getFile();
                $out[] = 'Line: ' . $e->getLine();
                $out[] = 'Code: ' . $e->getCode();
                echo '<ul><li>' . implode('</li><li>', $out) . '</li></ul>';
                exit();
            }
            echo '<p>An error occurred.<br />';
            echo 'Please contact us explaining what has happened.<br />';
            echo 'We are sorry for any inconvenience.</p>';
            exit();

        }
    }

}