<?php
$url = $this->objUrl->getCurrent(array('action', 'id'));
require_once('header.php');
?>
    <h1>Products :: Edit</h1>
    <p>The record has been updated successfully, but without changing the image.<br />
        <a href="<?php echo $url; ?>">Go back to the list of products.</a></p>
<?php require_once('footer.php'); ?>