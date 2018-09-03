<?php
$url = $this->objUrl->getCurrent(array('action', 'id'));
require_once('header.php');
?>
    <h1>Clients :: Edit</h1>
    <p>The record has been updated successfully.<br />
        <a href="<?php echo $url; ?>">Go back to the list of clients.</a></p>
<?php require_once('footer.php'); ?>