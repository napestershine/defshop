<?php

namespace App;

/**
 * Class Paging
 * @package App
 */
class Paging
{

    /**
     * @var Url|null
     */
    public $objUrl;

    /**
     * @var null
     */
    private $_records;

    /**
     * @var int
     */
    private $_max_pp;

    /**
     * @var
     */
    private $_numb_of_pages;

    /**
     * @var int
     */
    private $_numb_of_records;

    /**
     * @var int
     */
    private $_current;

    /**
     * @var int
     */
    private $_offset = 0;

    /**
     * @var string
     */
    public static $key = 'pg';

    /**
     * @var string
     */
    public $url;

    /**
     * Paging constructor.
     * @param null $objUrl
     * @param null $rows
     * @param int $max
     */
    public function __construct($objUrl = null, $rows = null, $max = 10)
    {
        $this->objUrl = is_object($objUrl) ? $objUrl : new Url();
        $this->_records = $rows;
        $this->_numb_of_records = count($this->_records);
        $this->_max_pp = $max;
        $this->url = $this->objUrl->getCurrent(self::$key);
        $current = $this->objUrl->get(self::$key);
        $this->_current = !empty($current) ? $current : 1;
        $this->numberOfPages();
        $this->getOffset();
    }

    /**
     *
     */
    private function numberOfPages()
    {
        $this->_numb_of_pages = ceil($this->_numb_of_records / $this->_max_pp);
    }

    /**
     *
     */
    private function getOffset()
    {
        $this->_offset = ($this->_current - 1) * $this->_max_pp;
    }

    /**
     * @return array|null
     */
    public function getRecords()
    {
        $out = array();
        if ($this->_numb_of_pages > 1) {
            $last = ($this->_offset + $this->_max_pp);

            for ($i = $this->_offset; $i < $last; $i++) {
                if ($i < $this->_numb_of_records) {
                    $out[] = $this->_records[$i];
                }
            }
        } else {
            $out = $this->_records;
        }
        return $out;
    }

    /**
     * @return string
     */
    private function getLinks()
    {
        if ($this->_numb_of_pages > 1) {

            $out = array();

            // first link
            if ($this->_current > 1) {
                $out[] = "<a href=\"" . $this->url . PAGE_EXT . "\">First</a>";
            } else {
                $out[] = "<span>First</span>";
            }

            // previous link
            if ($this->_current > 1) {

                // previous page number
                $id = ($this->_current - 1);

                $url = $id > 1 ?
                    $this->url . "/" . self::$key . "/" . $id . PAGE_EXT :
                    $this->url . PAGE_EXT;
                $out[] = "<a href=\"{$url}\">Previous</a>";

            } else {
                $out[] = "<span>Previous</span>";
            }

            // next link
            if ($this->_current != $this->_numb_of_pages) {
                // next page number
                $id = ($this->_current + 1);

                $url = $this->url . "/" . self::$key . "/" . $id . PAGE_EXT;
                $out[] = "<a href=\"{$url}\">Next</a>";

            } else {
                $out[] = "<span>Next</span>";
            }

            // last link
            if ($this->_current != $this->_numb_of_pages) {
                $url = $this->url . "/" . self::$key . "/" . $this->_numb_of_pages . PAGE_EXT;
                $out[] = "<a href=\"{$url}\">Last</a>";
            } else {
                $out[] = "<span>Last</span>";
            }

            return "<li>" . implode("</li><li>", $out) . "</li>";
        }

    }

    /**
     * @return string
     */
    public function getPaging()
    {
        $links = $this->getLinks();
        if (!empty($links)) {
            $out = "<ul class=\"paging\">";
            $out .= $links;
            $out .= "</ul>";
            return $out;
        }
    }

}
