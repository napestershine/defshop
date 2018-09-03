<?php
$url = $this->objUrl->getCurrent(array('action', 'id'));
require_once('header.php');
?>
    <h1>Business</h1>
    <p>The record has been updated successfully.<br />
        <a href="<?php echo $url; ?>">Go back to the business record.</a></p>
<?php require_once('footer.php'); ?>