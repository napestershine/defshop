<?php

use App\Catalogue;
use App\Business;
use App\Login;
use App\Session;
use App\Helper;
use App\Plugin;


$objCatalogue = new Catalogue();
$cats = $objCatalogue->getCategories();

$colors = $objCatalogue->getColorsFromProducts();

$objBusiness = new Business();
$business = $objBusiness->getOne(Business::BUSINESS_ID);
?>
<!DOCTYPE html>
<html>
<head>

    <title><?php echo $this->meta_title; ?></title>
    <meta name="description" content="<?php echo $this->meta_description; ?>"/>
    <meta http-equiv="imagetoolbar" content="no"/>
    <link href="<?php echo SITE_URL; ?>/css/core.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"/>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/"><?php echo $business['name']; ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
            <?php if (Login::isLogged(Login::$login_front)) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="#">Logged in as:
                        <?php echo Login::getFullNameFront(Session::getSession(Login::$login_front)); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $this->objUrl->href('orders'); ?>">
                        My Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $this->objUrl->href('logout'); ?>">
                        Logout
                    </a>
                </li>
            <?php else : ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $this->objUrl->href('login'); ?>">
                        Login
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div id="header">
    <div id="header_in">
        <h5><a href="/"><?php echo $business['name']; ?></a></h5>
        <?php
        if (Login::isLogged(Login::$login_front)) {
            echo '<div id="logged_as">Logged in as: <strong>';
            echo Login::getFullNameFront(Session::getSession(Login::$login_front));
            echo '</strong> | <a href="';
            echo $this->objUrl->href('orders');
            echo '">My orders</a>';
            echo ' | <a href="';
            echo $this->objUrl->href('logout');
            echo '">Logout</a></div>';
        } else {
            echo '<div id="logged_as"><a href="';
            echo $this->objUrl->href('login');
            echo '">Login</a></div>';
        }
        ?>
    </div>
</div>
<div id="outer">
    <div id="wrapper">
        <div id="left">
            <?php
            if ($this->objUrl->cpage !== 'summary') {
                echo Plugin::get('front' . DS . 'basket_left', array(
                    'objUrl' => $this->objUrl,
                    'objCurrency' => $this->objCurrency
                ));
            }
            ?>
            <?php if (!empty($cats)) { ?>
                <h2>Categories</h2>
                <ul id="navigation">
                    <?php
                    foreach ($cats as $cat) {
                        echo '<li><a href="';
                        echo $this->objUrl->href('catalogue', array('category', $cat['identity']));
                        echo '"';
                        echo $this->objNavigation->active('catalogue', array('category' => $cat['identity']));
                        echo '>';
                        echo Helper::encodeHtml($cat['name']);
                        echo '</a></li>';
                    }
                    ?>
                </ul>
            <?php } ?>

            <?php if (!empty($colors)) { ?>
                <h2>Colors</h2>
                <ul id="navigation">
                    <?php
                    foreach ($colors as $color) {
                        if (!empty($color)) {
                            echo '<li><a href="';
                            echo $this->objUrl->href('color', array('color', strtolower($color['color'])));
                            echo '"';
                            echo $this->objNavigation->active('color', array('color' => strtolower($color['color'])));
                            echo '>';
                            echo Helper::encodeHtml($color['color']);
                            echo '</a></li>';
                        }
                    }
                    ?>
                </ul>
            <?php } ?>
        </div>
        <div id="right">