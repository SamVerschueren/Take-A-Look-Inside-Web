<?php
require_once('includes/Router.php');

header('Access-Control-Allow-Origin: *');

/*
 * Create the Router instance and add the routing directions.
 * 
 * Regex rules based on The-Datatank project
 */
$router = new Router();
$router->addRoute('(?P<resource>[^/?.]+)/?(?P<parameters>[^.]*)(\.(?P<format>[^?]+))?.*', '{resource}Controller');

try {
    //router handles the request, redirects to correct controller
    $router->processRequest();
}
catch(Exception $ex) {
    //fail
    echo $ex->getMessage();
}
?>