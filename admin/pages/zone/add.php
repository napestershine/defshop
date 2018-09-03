<?php

use \Exception;

use App\Form;
use App\Validation;
use App\Plugin;
use App\Helper;


$objForm = new Form();
$objValid = new Validation($objForm);
$objValid->expected = array('name');
$objValid->required = array('name');

try {
	
	if ($objValid->isValid()) {
	
		if ($objShipping->addZone($objValid->post)) {
			
			$replace = array();
			
			$zones = $objShipping->getZones();
			$replace['#zoneList'] = Plugin::get('admin'.DS.'zone', array(
				'rows' => $zones,
				'objUrl' => $this->objUrl
			));
			
			echo Helper::json(array('error' => false, 'replace' => $replace));
			
		} else {
			$objValid->add2Errors('name', 'Record could not be added');
			throw new Exception('Record could not be added');
		}
		
	} else {
		throw new Exception('Invalid entry');
	}
	
} catch (Exception $e) {
	
	echo Helper::json(array('error' => true, 'validation' => $objValid->errorsMessages));
	
}