<?php

//debug mode
define('DEBUG', true);

//site settings
define('SITE_NAME', 'Faculty & Staff Directory');
define('BASE_URL', 'http://localhost/facultystaff/app/Views');

//paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', __DIR__ . '/app');
define('UPLOADS_PATH', __DIR__ . '/uploads');

//default image for members without photos
define('DEFAULT_IMAGE', 'assets/images/default-user.png');

//error handling
if (DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

