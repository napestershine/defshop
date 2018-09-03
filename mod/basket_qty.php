<?php

require_once('../inc/autoload.php');
if (isset($_POST['qty']) && isset($_POST['id'])) {
    $out = array();

    $id = $_POST['id'];
    $val = $_POST['qty'];

    $objCatalogue = new App\Catalogue();

    $product = $objCatalogue->getProduct($id);

    if (!empty($product)) {
        switch ($val) {
            case 0:
                App\Session::removeItem($id);
                break;
            default:
                App\Session::setItem($id, $val);
        }
    }
}
?>