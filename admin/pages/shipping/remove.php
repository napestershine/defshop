<?php

use \Exception;

use App\Helper;

if ($objShipping->removeType($type['id'])) {
	echo Helper::json(array('error' => false));
} else {
	throw new Exception('Record could not be removed');
}