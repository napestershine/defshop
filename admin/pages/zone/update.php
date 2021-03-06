<?php

use \Exception;

use App\Form;
use App\Helper;


$objForm = new Form();

$value = $objForm->getPost('value');

if (!empty($value)) {
	
	if ($objShipping->updateZone(array('name' => $value), $zone['id'])) {
		
		echo Helper::json(array('error' => false));
		
	} else {
		throw new Exception('Record could not be updated');
	}
	
} else {
	throw new Exception('Invalid entry');
}