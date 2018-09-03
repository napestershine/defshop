<?php

require_once ("../inc/autoload.php");
$objBasket = new App\Basket();
$out = array();
$out['bl_ti'] = $objBasket->number_of_items;
$out['bl_st'] = number_format($objBasket->sub_total, 2);
$out['bl_vat'] = number_format($objBasket->vat, 2);
$out['bl_total'] = number_format($objBasket->total, 2);
echo json_encode($out);
?>