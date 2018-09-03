<?php

namespace App;

/**
 * Class Basket
 * @package App
 */
class Basket
{

    /**
     * @var Catalogue
     */
    private $_objCatalogue;

    /**
     * @var bool
     */
    public $empty_basket;

    /**
     * @var int
     */
    public $vat_rate;

    /**
     * @var
     */
    public $number_of_items;

    /**
     * @var
     */
    public $sub_total;

    /**
     * @var
     */
    private $_vat;

    /**
     * @var
     */
    private $_total;

    /**
     * @var
     */
    public $weight;

    /**
     * @var
     */
    private $_array_weight;

    /**
     * @var
     */
    public $final_shipping_type;

    /**
     * @var
     */
    public $final_shipping_cost;

    /**
     * @var
     */
    public $final_sub_total;

    /**
     * @var
     */
    public $final_vat;

    /**
     * @var
     */
    public $final_total;

    /**
     * @var null
     */
    private $_user;

    /**
     * Basket constructor.
     * @param null $user
     */
    public function __construct($user = null)
    {

        if (!empty($user)) {
            $this->_user = $user;
        }

        $this->_objCatalogue = new Catalogue();
        $this->empty_basket = empty($_SESSION['basket']) ? true : false;

        if (!empty($this->_user) && ($this->_user['country'] == COUNTRY_LOCAL || INTERNATIONAL_VAT)) {
            $objBusiness = new Business();
            $this->vat_rate = $objBusiness->getVatRate();
        } else {
            $this->vat_rate = 0;
        }

        $this->_noItems();
        $this->_subtotal();
        $this->_vat();
        $this->_total();
        $this->_process();

    }

    /**
     *
     */
    private function _noItems()
    {
        $value = 0;
        if (!$this->empty_basket) {
            foreach ($_SESSION['basket'] as $key => $basket) {
                $value += $basket['qty'];
            }
        }
        $this->number_of_items = $value;
    }

    /**
     *
     */
    private function _subtotal()
    {
        $value = 0;
        if (!$this->empty_basket) {
            foreach ($_SESSION['basket'] as $key => $basket) {
                $product = $this->_objCatalogue->getProduct($key);
                $value += ($basket['qty'] * $product['price']);
                $this->_array_weight[] = ($basket['qty'] * $product['weight']);
            }
        }
        $this->weight = array_sum($this->_array_weight);
        $this->sub_total = round($value, 2);
    }

    /**
     *
     */
    private function _vat()
    {
        $value = 0;
        if (!$this->empty_basket) {
            $value = ($this->vat_rate * ($this->sub_total / 100));
        }
        $this->_vat = round($value, 2);
    }

    /**
     *
     */
    private function _total()
    {
        $this->_total = round(($this->sub_total + $this->_vat), 2);
    }

    /**
     * @param $sess_id
     * @return string
     */
    public static function activeButton($sess_id)
    {
        if (isset($_SESSION['basket'][$sess_id])) {
            $id = 0;
            $label = "Remove from basket";
        } else {
            $id = 1;
            $label = "Add to basket";
        }

        $out = "<a href=\"#\" class=\"add_to_basket";
        $out .= $id == 0 ? " red" : null;
        $out .= "\" rel=\"";
        $out .= $sess_id . "_" . $id;
        $out .= "\">{$label}</a>";
        return $out;
    }

    /**
     * @param null $price
     * @param null $qty
     * @return float
     */
    public function itemTotal($price = null, $qty = null)
    {
        if (!empty($price) && !empty($qty)) {
            return round(($price * $qty), 2);
        }
    }

    /**
     * @param null $id
     * @return string
     */
    public static function removeButton($id = null)
    {
        if (!empty($id)) {
            if (isset($_SESSION['basket'][$id])) {
                $out = "<a href=\"#\" class=\"remove_basket red";
                $out .= "\" rel=\"{$id}\">Remove</a>";
                return $out;
            }
        }
    }

    /**
     *
     */
    private function _process()
    {

        $this->final_shipping_type = Session::getSession('shipping_type');
        $this->final_shipping_cost = Session::getSession('shipping_cost');
        $this->final_sub_total = round(($this->sub_total + $this->final_shipping_cost), 2);
        $this->final_vat = round(($this->vat_rate * ($this->final_sub_total / 100)), 2);
        $this->final_total = round(($this->final_sub_total + $this->final_vat), 2);

    }

    /**
     * @param null $shipping
     * @return bool
     */
    public function addShipping($shipping = null)
    {
        if (!empty($shipping)) {
            Session::setSession('shipping_id', $shipping['id']);
            Session::setSession('shipping_cost', $shipping['cost']);
            Session::setSession('shipping_type', $shipping['name']);
            $this->_process();
            return true;
        }
        return false;
    }

    /**
     *
     */
    public function clearShipping()
    {

        Session::clear('shipping_id');
        Session::clear('shipping_cost');
        Session::clear('shipping_type');

        $this->final_shipping_type = null;
        $this->final_shipping_cost = null;
        $this->final_sub_total = null;
        $this->final_vat = null;
        $this->final_total = null;

    }

}
