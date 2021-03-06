<?php

namespace App;

/**
 * Class Form
 * @package App
 */
class Form
{

    /**
     * @param null $field
     * @return bool
     */
    public function isPost($field = null)
    {
        if (!empty($field)) {
            if (isset($_POST[$field])) {
                return true;
            }
            return false;
        } else {
            if (!empty($_POST)) {
                return true;
            }
            return false;
        }
    }

    /**
     * @param null $field
     * @return null|string
     */
    public function getPost($field = null)
    {
        if (!empty($field)) {
            return $this->isPost($field) ?
                strip_tags($_POST[$field]) :
                null;
        }
    }

    /**
     * @param $field
     * @param $value
     * @param null $default
     * @return null|string
     */
    public function stickySelect($field, $value, $default = null)
    {
        if ($this->isPost($field) && $this->getPost($field) == $value) {
            return " selected=\"selected\"";
        } else {
            return !empty($default) && $default == $value ?
                " selected=\"selected\"" :
                null;
        }
    }

    /**
     * @param $field
     * @param null $value
     * @return null|string
     */
    public function stickyText($field, $value = null)
    {
        if ($this->isPost($field)) {
            return stripslashes($this->getPost($field));
        } else {
            return !empty($value) ? $value : null;
        }
    }

    /**
     * @param null $field
     * @param null $value
     * @param null $data
     * @return null|string
     */
    public function stickyRadio($field = null, $value = null, $data = null)
    {

        $post = $this->getPost($field);

        if (!Helper::isEmpty($post)) {
            if ($post == $value) {
                return ' checked="checked"';
            }
        } else {
            return !Helper::isEmpty($data) && $value == $data ? ' checked="checked"' : null;
        }
    }

    /**
     * @param null $field
     * @param null $value
     * @param null $data
     * @param null $class
     * @param bool $single
     * @return string
     */
    public function stickyRemoveClass(
        $field = null, $value = null, $data = null, $class = null, $single = false
    )
    {
        $post = $this->getPost($field);

        if (!Helper::isEmpty($post)) {
            if ($post != $value) {
                return $single ? ' class="' . $class . '"' : ' ' . $class;
            }
        } else {
            if ($value != $data) {
                return $single ? ' class="' . $class . '"' : ' ' . $class;
            }
        }
    }

    /**
     * @param null $record
     * @param string $name
     * @param bool $selectOption
     * @return string
     */
    public function getCountriesSelect($record = null, $name = 'country', $selectOption = false)
    {
        $objCountry = new Country();
        $countries = $objCountry->getCountries();
        if (!empty($countries)) {
            $out = "<select name=\"{$name}\" id=\"{$name}\" class=\"sel\">";
            if (empty($record) || $selectOption == true) {
                $out .= "<option value=\"\">Select one&hellip;</option>";
            }
            foreach ($countries as $country) {
                $out .= "<option value=\"";
                $out .= $country['id'];
                $out .= "\"";
                $out .= $this->stickySelect($name, $country['id'], $record);
                $out .= ">";
                $out .= $country['name'];
                $out .= "</option>";
            }
            $out .= "</select>";
            return $out;
        }
    }

    /**
     * @param null $expected
     * @return array
     */
    public function getPostArray($expected = null)
    {
        $out = array();
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                if (!empty($expected)) {
                    if (in_array($key, $expected)) {
                        $out[$key] = strip_tags($value);
                    }
                } else {
                    $out[$key] = strip_tags($value);
                }
            }
        }
        return $out;
    }

}
