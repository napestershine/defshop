<?php

use App\Login;

Login::logout(Login::$login_front);
Login::restrictFront($this->objUrl);