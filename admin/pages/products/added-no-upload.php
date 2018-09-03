<?php
$url = $this->objUrl->getCurrent(array('action', 'id'));
require_once('header.php');
?>
    <h1>Products :: Add</h1>
    <p>The new record has been added successfully without the image.<br />
        <a href="<?php echo $url; ?>">Go back to the list of products.</a></p>
<?php require_once('footer.php'); ?>