<?php


$routes = [
    //public routes
    // '/' => [
    //     'controller' => 'ListController',
    //     'action' => 'displayHome'
    // ],
    '/login.php' => [
        'controller' => 'ListController',
        'action' => 'displayLogin'
    ],
    
    //admin routes
    '/admin/manage-members.php' => [
        'controller' => 'ListController',
        'action' => 'manageMembers',
        'auth' => true
    ],
    '/admin/add-member.php' => [
        'controller' => 'ListController',
        'action' => 'addMember',
        'auth' => true
    ],
    '/admin/edit-member.php' => [
        'controller' => 'ListController',
        'action' => 'editMember',
        'auth' => true
    ],
    '/admin/delete-member.php' => [
        'controller' => 'ListController',
        'action' => 'deleteMember',
        'auth' => true
    ],
    '/admin/member-details.php' => [
        'controller' => 'ListController',
        'action' => 'getMemberDetails',
        'auth' => true
    ],
    '/admin/logout.php' => [
        'controller' => 'ListController',
        'action' => 'logout',
        'auth' => true
    ]
];

//simple router function (for a more advanced project, you'd use a proper router)
function route($uri, $routes) {
    //remove query parameters for matching
    $uri_parts = explode('?', $uri);
    $base_uri = $uri_parts[0];
    
    if (isset($routes[$base_uri])) {
        $route = $routes[$base_uri];
        
        //check if authentication is required
        if (isset($route['auth']) && $route['auth'] && !is_logged_in()) {
            //redirect to login page
            redirect(BASE_URL . '/login.php');
        }
        
        //load controller and call action
        $controller_name = $route['controller'];
        $action = $route['action'];
        
        require_once APP_PATH . '/Controllers/' . $controller_name . '.php';
        $controller = new $controller_name($GLOBALS['conn']);
        $controller->$action();
    } else {
        //handle 404 - Page not found
        header('HTTP/1.0 404 Not Found');
        //load 404 view
    }
}