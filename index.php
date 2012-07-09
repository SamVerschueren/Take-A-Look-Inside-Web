<?php
require_once('includes/Router.php');

/*
 * Create the Router instance and add the routing directions.
 * 
 * Regex rules based on The-Datatank project
 */
$router = new Router();
$router->addRoute('(?P<resource>[^/.]+)/?(?P<parameters>[^.]*)\.(?P<format>[^?]+).*', '{resource}Controller');

try {
    $router->processRequest();
}
catch(Exception $ex) {
    echo $ex->getMessage();
}
?>