<?php

use App\Catalogue;
use App\Helper;
use App\Paging;

$id = $this->objUrl->get('id');

if (!empty($id)) {

    $objCatalogue = new Catalogue();
    $category = $objCatalogue->getCategory($id);

    if (!empty($category)) {

        $yes = $this->objUrl->getCurrent() . '/remove/1';
        $no = 'javascript:history.go(-1)';

        $remove = $this->objUrl->get('remove');

        if (!empty($remove) && $category['products_count'] == 0) {

            $objCatalogue->removeCategory($id);

            Helper::redirect($this->objUrl->getCurrent(array('action', 'id', 'remove', 'srch', Paging::$key)));

        }

        require_once('header.php');
        ?>
        <h1>Categories :: Remove</h1>
        <p>Are you sure you want to remove this record?<br/>
            There is no undo!<br/>
            <a href="<?php echo $yes; ?>">Yes</a> | <a href="<?php echo $no; ?>">No</a></p>
        <?php
        require_once('footer.php');
    }
}
?>