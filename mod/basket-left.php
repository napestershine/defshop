<?php

use App\Plugin;

echo Plugin::get('front'.DS.'basket_left', array(
    'objUrl' => $this->objUrl,
    'objCurrency' => $this->objCurrency
));