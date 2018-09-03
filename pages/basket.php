<?php

use App\Plugin;

require_once('header.php');
?>

    <h1>Basket</h1>

    <div id="big_basket">
        <?php
        echo Plugin::get(
            'front' . DS . 'basket_view',
            [
                'objUrl' => $this->objUrl,
                'objCurrency' => $this->objCurrency
            ]);
        ?>
    </div>

<?php require_once('footer.php'); ?>