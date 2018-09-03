<?php

require_once('../inc/autoload.php');
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    App\Session::removeItem($id);
}
?>