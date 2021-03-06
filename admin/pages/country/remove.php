<?php

use \Exception;

use App\Helper;
use App\Plugin;


if ($objCountry->delete($country['id'])) {

    $replace = array();

    $countries = $objCountry->getAll();

    $replace['#countryList'] = Plugin::get('admin'.DS.'country', array(
        'rows' => $countries,
        'objUrl' => $this->objUrl
    ));

    echo Helper::json(array('error' => false, 'replace' => $replace));

} else {
    throw new Exception('Record could not be removed');
}