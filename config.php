<?php

//debug mode
define('DEBUG', true);

//site settings
define('SITE_NAME', 'Faculty, Staff & Office Directory');
define('BASE_URL', 'http://localhost/facultystaffofficedirectory');

//paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', __DIR__ . '/app');
define('GLOBAL_PATH', __DIR__ . '/FACULTYSTAFFOFFICEDIRECTORY');
define('UPLOADS_PATH', __DIR__ . '/uploads');

//default image for lists without photos
define('DEFAULT_IMAGE', 'assets/images/default-user.png');

//error handling
if (DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

