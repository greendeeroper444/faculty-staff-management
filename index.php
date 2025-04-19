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
        
    
        // include $error_file;
        return false;
    }
}

//router instance
$router = new Router();

//register routes
$router->addRoute('login', APP_PATH . '/app/Views/admin/login.php');
$router->addRoute('logout', APP_PATH . '/Controllers/logout.php');
$router->addRoute('admin', APP_PATH . '/app/Views/admin/index.php');
$router->addRoute('admin/dashboard', APP_PATH . '/app/Views/admin/index.php');
$router->addRoute('admin/faculty-staff-list', APP_PATH . '/app/Views/admin/faculty-staff-list.php', ['type' => 'faculty']);
$router->addRoute('admin/faculty-staff-list-details/:id', APP_PATH . '/app/Views/admin/faculty-staff-list-details.php');
$router->addRoute('faculty-staff-list', APP_PATH . '/app/Views/user/faculty-staff-list.php');
$router->addRoute('faculty-staff-list/:id', APP_PATH . '/app/Views/user/faculty-staff-list-details.php');
$router->addRoute('faculty-staff-list.php', APP_PATH . '/Views/faculty-staff-list.php');
$router->addRoute('', APP_PATH . '/app/Views/index.php');

//handle the current request
$router->handleRequest();