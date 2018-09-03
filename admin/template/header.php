<?php

use App\Login;
use App\Session;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Ecommerce website project</title>
    <meta name="description" content="Ecommerce website project"/>
    <meta http-equiv="imagetoolbar" content="no"/>
    <link href="<?php echo SITE_URL; ?>/css/core.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"/>
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/">Admin: Content Management System</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ml-auto">
            <?php if (Login::isLogged(Login::$login_admin)) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="#">Logged in as:
                        <?php echo $this->objAdmin->getFullNameAdmin(Session::getSession(Login::$login_admin)); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/panel/logout">
                        Logout
                    </a>
                </li>
            <?php else : ?>
                <li class="nav-item">
                    <a class="nav-link" href="/panel/login">
                        Login
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<div id="outer">
    <div id="wrapper">
        <div id="left">
            <?php if (Login::isLogged(Login::$login_admin)) { ?>
                <h2>Navigation</h2>
                <div class="dev br_td">&nbsp;</div>
                <ul id="navigation">
                    <li>
                        <a href="/panel/products"
                            <?php echo $this->objNavigation->active('products'); ?>>
                            products
                        </a>
                    </li>
                    <li>
                        <a href="/panel/categories"
                            <?php echo $this->objNavigation->active('categories'); ?>>
                            categories
                        </a>
                    </li>
                    <li>
                        <a href="/panel/orders"
                            <?php echo $this->objNavigation->active('orders'); ?>>
                            orders
                        </a>
                    </li>
                    <li>
                        <a href="/panel/clients"
                            <?php echo $this->objNavigation->active('clients'); ?>>
                            clients
                        </a>
                    </li>
                    <li>
                        <a href="/panel/business"
                            <?php echo $this->objNavigation->active('business'); ?>>
                            business
                        </a>
                    </li>
                    <li>
                        <a href="/panel/shipping"
                            <?php echo $this->objNavigation->active('shipping'); ?>>
                            shipping
                        </a>
                    </li>
                    <li>
                        <a href="/panel/zone"
                            <?php echo $this->objNavigation->active('zone'); ?>>
                            zones
                        </a>
                    </li>
                    <li>
                        <a href="/panel/country"
                            <?php echo $this->objNavigation->active('country'); ?>>
                            countries
                        </a>
                    </li>
                </ul>
            <?php } else { ?>
                &nbsp;
            <?php } ?>
        </div>
        <div id="right">
		
		
		
		
		
		
		
		
		