<?php

use App\Login;

Login::logout(Login::$login_admin);
Login::restrictAdmin();