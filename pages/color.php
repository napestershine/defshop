<?php

use App\Catalogue;
use App\Paging;
use App\Helper;
use App\Basket;

$color = $this->objUrl->get('color');

if (empty($color)) {
    require_once('error.php');
} else {

    $objCatalogue = new Catalogue();

    $rows = $objCatalogue->getProductsByColor($color);

    if (empty($rows)) {
        require_once('error.php');
    } else {

        // instantiate paging class
        $objPaging = new Paging($this->objUrl, $rows, 3);
        $rows = $objPaging->getRecords();

        require_once('header.php');
        ?>

        <h1>Color :: <?php echo $color; ?></h1>

        <?php
        if (!empty($rows)) {
            foreach ($rows as $row) {
                ?>

                <div class="catalogue_wrapper">
                    <div class="catalogue_wrapper_left">
                        <?php

                        $image = !empty($row['image']) ?
                            $row['image'] :
                            'unavailable.png';

                        $width = Helper::getImgSize(CATALOGUE_PATH . DS . $image, 0);
                        $width = $width > 120 ? 120 : $width;

                        $link = $this->objUrl->href('catalogue-item', array(
                            'category',
                            $category['identity'],
                            'item',
                            $row['identity']
                        ));

                        ?>
                        <a href="<?php echo $link; ?>"><img
                                    src="<?php echo SITE_URL . DS . CATALOGUE_DIR . DS . $image; ?>"
                                    alt="<?php echo Helper::encodeHtml($row['name'], 1); ?>"
                                    width="<?php echo $width; ?>"/></a>
                    </div>
                    <div class="catalogue_wrapper_right">
                        <h4><a href="<?php echo $link; ?>"><?php echo Helper::encodeHtml($row['name'], 1); ?></a></h4>
                        <p><?php echo Helper::encodeHtml($row['color'], 1); ?></p>
                        <h4>Price: <?php echo $this->objCurrency->display(number_format($row['price'], 2)); ?></h4>
                        <p><?php echo Helper::shortenString(Helper::encodeHtml($row['description'])); ?></p>
                        <p><?php echo Basket::activeButton($row['id']); ?></p>
                    </div>
                </div>

                <?php
            }

            echo $objPaging->getPaging();

        } else {
            ?>
            <p>There are no products in this color.</p>
            <?php
        }
        require_once("footer.php");

    }
}
?>