// Inside the handleRequest method, replace the final section:

// No route matched - Make sure this file exists!
header("HTTP/1.0 404 Not Found");

// Create the error directory if it doesn't exist
$error_dir = APP_PATH . '/Views/errors';
if (!is_dir($error_dir)) {
    mkdir($error_dir, 0755, true);
}

// Check if 404 page exists, if not create a simple one
$error_file = $error_dir . '/404.php';
if (!file_exists($error_file)) {
    file_put_contents($error_file, '<!DOCTYPE html>
    <html>
    <head>
        <title>404 - Page Not Found</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
            h1 { color: #333; }
            p { color: #666; }
        </style>
    </head>
    <body>
        <h1>404 - Page Not Found</h1>
        <p>The page you are looking for does not exist.</p>
        <p><a href="/">Return to homepage</a></p>
    </body>
    </html>');
}

include $error_file;
exit(); // Add this line to stop further execution
return false;