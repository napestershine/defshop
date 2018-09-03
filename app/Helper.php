<?php

namespace App;

/**
 * Class Helper
 * @package App
 */
class Helper
{

    /**
     * @param $string
     * @param int $case
     * @return mixed|string
     */
    public static function encodeHTML($string, $case = 2)
    {
        switch ($case) {
            case 1:
                return htmlentities($string, ENT_NOQUOTES, 'UTF-8', false);
                break;
            case 2:
                $pattern = '<([a-zA-Z0-9\.\, "\'_\/\-\+~=;:\(\)?&#%![\]@]+)>';
                // put text only, devided with html tags into array
                $textMatches = preg_split('/' . $pattern . '/', $string);
                // array for sanitised output
                $textSanitised = array();
                foreach ($textMatches as $key => $value) {
                    $textSanitised[$key] = htmlentities(html_entity_decode($value, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');
                }
                foreach ($textMatches as $key => $value) {
                    $string = str_replace($value, $textSanitised[$key], $string);
                }
                return $string;
                break;
        }
    }

    /**
     * @param $image
     * @param $case
     * @return mixed
     */
    public static function getImgSize($image, $case)
    {
        if (is_file($image)) {
            // 0 => width, 1 => height, 2 => type, 3 => attributes
            $size = getimagesize($image);
            return $size[$case];
        }
    }

    /**
     * @param $string
     * @param int $len
     * @return string
     */
    public static function shortenString($string, $len = 150)
    {
        if (strlen($string) > $len) {
            $string = trim(substr($string, 0, $len));
            $string = substr($string, 0, strrpos($string, " ")) . "&hellip;";
        } else {
            $string .= "&hellip;";
        }
        return $string;
    }

    /**
     * @param null $url
     */
    public static function redirect($url = null)
    {
        if (!empty($url)) {
            header("Location: {$url}");
            exit;
        }
    }

    /**
     * @param null $case
     * @param null $date
     * @return bool|string
     */
    public static function setDate($case = null, $date = null)
    {
        $date = empty($date) ? time() : strtotime($date);

        switch ($case) {
            case 1:
                // 01/01/2010
                return date('d/m/Y', $date);
                break;
            case 2:
                // Monday, 1st January 2010, 09:30:56
                return date('l, jS F Y, H:i:s', $date);
                break;
            case 3:
                // 2010-01-01-09-30-56
                return date('Y-m-d-H-i-s', $date);
                break;
            default:
                return date('Y-m-d H:i:s', $date);
        }
    }

    /**
     * @param null $name
     * @return string
     */
    public static function cleanString($name = null)
    {
        if (!empty($name)) {
            return strtolower(preg_replace('/[^a-zA-Z0-9.]/', '-', $name));
        }
    }

    /**
     * @param null $string
     * @param null $array
     * @return mixed|null
     */
    public static function clearString($string = null, $array = null)
    {
        if (!empty($string) && !self::isEmpty($array)) {
            $array = self::makeArray($array);
            foreach ($array as $key => $value) {
                $string = str_replace($value, '', $string);
            }
            return $string;
        }
    }

    /**
     * @param null $value
     * @return bool
     */
    public static function isEmpty($value = null)
    {
        return empty($value) && !is_numeric($value) ? true : false;
    }

    /**
     * @param null $array
     * @return array|null
     */
    public static function makeArray($array = null)
    {
        return is_array($array) ? $array : array($array);
    }

    /**
     * @param null $array
     * @return string
     */
    public static function printArray($array = null)
    {
        ob_start();
        echo '<pre>';
        print_r($array);
        echo '</pre>';
        return ob_get_clean();
    }

    /**
     * @param null $string
     * @return mixed
     */
    public static function alphaNumericalOnly($string = null)
    {
        if (!empty($string)) {
            return preg_replace("/[^A-Za-z0-9]/", '', $string);
        }
    }

    /**
     * @param null $input
     * @return string
     */
    public static function json($input = null)
    {
        if (!empty($input)) {
            if (defined("JSON_UNESCAPED_UNICODE")) {
                return json_encode($input, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
            }
            return json_encode($input, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        }
    }

    /**
     * @param null $array
     * @return bool
     */
    public static function isArrayEmpty($array = null)
    {
        return (empty($array) || !is_array($array));
    }

}
