<?php $objBasket = new App\Basket(); ?>

<h2>Your Basket</h2>
<dl id="basket_left">
    <dt>No. of items:</dt>
    <dd class="bl_ti"><span><?php echo $objBasket->number_of_items; ?></span></dd>
    <dt>Sub-total:</dt>
    <dd class="bl_st">&dollar;<span><?php echo number_format($objBasket->sub_total, 2); ?></span></dd>
    <dt>VAT(<span><?php echo $objBasket->vat_rate; ?></span>%):</dt>
    <dd class="bl_vat">&dollar;<span><?php echo number_format($objBasket->vat, 2); ?></span></dd>
    <dt>Total (inc):</dt>
    <dd class="bl_total">&dollar;<span><?php echo number_format($objBasket->total, 2); ?></span></dd>
</dl>
<div class="dev br_td">&#160;</div>
<p>
    <a href="<?php echo SITE_URL; ?>?page=basket">View Basket</a>
    | <a href="<?php echo SITE_URL; ?>?page=checkout">Checkout</a>
</p>
<div class="dev br_td">&#160;</div>