<?php

namespace App;

/**
 * Class Login
 * @package App
 */
class Login
{

    /**
     * @var string
     */
    public static $login_page_front = "/login";

    /**
     * @var string
     */
    public static $dashboard_front = "/orders";

    /**
     * @var string
     */
    public static $login_front = "cid";

    /**
     * @var string
     */
    public static $login_page_admin = "/panel/";

    /**
     * @var string
     */
    public static $dashboard_admin = "/panel/products";

    /**
     * @var string
     */
    public static $login_admin = "aid";

    /**
     * @var string
     */
    private static $_valid_login = "valid";

    /**
     * @var string
     */
    public static $referrer = "refer";

    /**
     * @param null $case
     * @return bool
     */
    public static function isLogged($case = null)
    {
        if (!empty($case)) {
            if (
                isset($_SESSION[self::$_valid_login]) &&
                $_SESSION[self::$_valid_login] == 1
            ) {
                return isset($_SESSION[$case]) ? true : false;
            }
            return false;
        }
        return false;
    }

    /**
     * @param null $id
     * @param null $url
     */
    public static function loginFront($id = null, $url = null)
    {
        if (!empty($id)) {
            $url = !empty($url) ? $url : self::$dashboard_front . PAGE_EXT;
            $_SESSION[self::$login_front] = $id;
            $_SESSION[self::$_valid_login] = 1;
            Helper::redirect($url);
        }
    }

    /**
     * @param null $id
     * @param null $url
     */
    public static function loginAdmin($id = null, $url = null)
    {
        if (!empty($id)) {
            $url = !empty($url) ? $url : self::$dashboard_admin;
            $_SESSION[self::$login_admin] = $id;
            $_SESSION[self::$_valid_login] = 1;
            Helper::redirect($url);
        }
    }

    /**
     * @param null $objUrl
     */
    public static function restrictFront($objUrl = null)
    {
        $objUrl = is_object($objUrl) ? $objUrl : new Url();
        if (!self::isLogged(self::$login_front)) {
            $url = $objUrl->cpage != "logout" ?
                self::$login_page_front . "/" . self::$referrer . "/" . $objUrl->cpage . PAGE_EXT :
                self::$login_page_front . PAGE_EXT;
            Helper::redirect($url);
        }
    }

    /**
     *
     */
    public static function restrictAdmin()
    {
        if (!self::isLogged(self::$login_admin)) {
            Helper::redirect(self::$login_page_admin);
        }
    }

    /**
     * @param null $string
     * @return string
     */
    public static function string2hash($string = null)
    {
        if (!empty($string)) {
            return hash('sha512', $string);
        }
    }

    /**
     * @param null $id
     * @return string
     */
    public static function getFullNameFront($id = null)
    {
        if (!empty($id)) {
            $objUser = new User();
            $user = $objUser->getUser($id);
            if (!empty($user)) {
                return $user['first_name'] . " " . $user['last_name'];
            }
        }
    }

    /**
     * @param null $case
     */
    public static function logout($case = null)
    {
        if (!empty($case)) {
            $_SESSION[$case] = null;
            $_SESSION[self::$_valid_login] = null;
            unset($_SESSION[$case]);
            unset($_SESSION[self::$_valid_login]);
        } else {
            session_destroy();
        }
    }

}
