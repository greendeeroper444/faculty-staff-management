<?php

//start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db-connect.php';
require_once __DIR__ . '/includes/functions.php';

class Router {
    private $routes = [];
    
    public function addRoute($url, $file, $params = []) {
        $this->routes[$url] = [
            'file' => $file,
            'params' => $params
        ];
    }

    public function handleRequest() {
        //get the requested URL
        $request_uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($request_uri, PHP_URL_PATH);
        
        //remove script name and query string
        $base_dir = dirname($_SERVER['SCRIPT_NAME']);
        if (strpos($path, $base_dir) === 0) {
            $path = substr($path, strlen($base_dir));
        }
        
        //remove leading/trailing slashes
        $path = trim($path, '/');
        
        //default to index if empty
        if (empty($path)) {
            $path = 'index';
        }
        
        //loop through registered routes to find a match
        foreach ($this->routes as $route => $route_info) {
            //convert route to regex pattern
            $pattern = '#^' . str_replace([':id', '/'], ['([0-9]+)', '\/'], $route) . '$#';
            
            if (preg_match($pattern, $path, $matches)) {
                //remove the full match
                array_shift($matches);
                
                //get the file to include
                $file = $route_info['file'];
                
                //extract parameters
                $params = $route_info['params'];
                
                //add captured parameters
                if (!empty($matches)) {
                    $params['id'] = $matches[0];
                }
                
                //extract query parameters
                $query = [];
                parse_str(parse_url($request_uri, PHP_URL_QUERY) ?? '', $query);
                $params = array_merge($params, $query);
                
                //include the file and pass parameters
                foreach ($params as $key => $value) {
                    $_GET[$key] = $value;
                }
                
                //check if file exists
                if (file_exists($file)) {
                    require $file;
                    return true;
                }
            }
        }
        
        //no route matched - Make sure this file exists!
        header("HTTP/1.0 404 Not Found");
        
        // // Create the error directory if it doesn't exist
        // $error_dir = APP_PATH . '/Views/errors';
        // if (!is_dir($error_dir)) {
        //     mkdir($error_dir, 0755, true);
        // }
        
        // // Check if 404 page exists, if not create a simple one
        // $error_file = $error_dir . '/404.php';
        // if (!file_exists($error_file)) {
        //     file_put_contents($error_file, '<?php
        //     <!DOCTYPE html>
        //     <html>
        //     <head>
        //         <title>404 - Page Not Found</title>
        //         <style>
        //             body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        //             h1 { color: #333; }
        //             p { color: #666; }
        //         </style>
        //     </head>
        //     <body>
        //         <h1>404 - Page Not Found</h1>
        //         <p>The page you are looking for does not exist.</p>
        //         <p><a href="/">Return to homepage</a></p>
        //     </body>
        //     </html>');
        // }
        
        // include $error_file;
        return false;
    }
}

//router instance
$router = new Router();

//register routes
$router->addRoute('login', APP_PATH . '/Views/login.php');
$router->addRoute('logout', APP_PATH . '/Controllers/logout.php');
$router->addRoute('admin', APP_PATH . '/Views/admin/index.php');
$router->addRoute('admin/dashboard', APP_PATH . '/Views/admin/index.php');
$router->addRoute('admin/manage-members', APP_PATH . '/Views/admin/manage-members.php', ['type' => 'faculty']);
$router->addRoute('admin/manage-members/:id', APP_PATH . '/Views/admin/edit-member.php');
$router->addRoute('admin/manage-details/:id', APP_PATH . '/Views/admin/member-details.php');
$router->addRoute('faculty', APP_PATH . '/Views/faculty.php');
$router->addRoute('staff', APP_PATH . '/Views/staff.php');
$router->addRoute('about', APP_PATH . '/Views/about.php');
$router->addRoute('contact', APP_PATH . '/Views/contact.php');
$router->addRoute('', APP_PATH . '/Views/index.php');

//handle the current request
$router->handleRequest();