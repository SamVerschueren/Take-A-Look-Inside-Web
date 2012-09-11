<?php
require_once('system/web/routing/Router.php');
require_once('system/web/mvc/ViewDataDictionary.php');
require_once('system/web/mvc/ViewEngine.php');
require_once('system/exceptions/ClassNotFoundException.php');
require_once('system/exceptions/runtime/UnsupportedOperationException.php');
require_once('system/data/common/DbConnection.php');

require_once('config/Config.php');

header('Access-Control-Allow-Origin: *');

function __autoload($class) {
    /**
     * If the file does not exists, the class does not exists.
     */
    if(!file_exists('controllers/' . $class . '.php') && !file_exists('system/exceptions/' . $class . '.php') && !file_exists('viewmodels/' . $class . '.php')) {
        throw new ClassNotFoundException('Class ' . $class . ' does not exists.');        
    }

    if(strpos($class, 'Controller')) {
        require_once('controllers/' . $class . '.php');    
    }
    else if(strpos($class, 'Exception')) {
        require_once('system/exceptions/' . $class . '.php');
    }
    else if(strpos($class, 'ViewModel')) {
        require_once('viewmodels/' . $class . '.php');
    }
}

/*
 * Connect with the database
 */
$dbConnection = new DbConnection(Config::$DB_HOST, Config::$DB_USER, Config::$DB_PASSWORD);
$dbConnection->connect();
$dbConnection->selectDatabase(Config::$DB);

/*
 * Create the Router instance and add the routing directions.
 */
$router = new Router();
$router->addRoute('(?P<controller>[^/?]*)/?(?P<action>[^/?]*)/?(?P<id>[^?]*)?.*', '{controller}Controller');

try {
    //router handles the request, redirects to correct controller
    $actionResult = $router->processRequest();
    $actionResult->executeResult();
}
catch(Exception $ex) {
    //fail
    exit($ex->getMessage());
}

$viewEngine = ViewEngine::getInstance();
$viewEngine->getViewResult()->setMasterName('_layout');

$viewData = $viewEngine->getViewResult()->getViewData();

function renderBody() {    
    ViewEngine::getInstance()->render();
}

include('views/shared/' . $viewEngine->getViewResult()->getMasterName() . '.phtml');
?>