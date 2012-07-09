<?php
require_once('Reader.php');
require_once('persistence/Connection.php');
require_once('Config.php');
require_once('exceptions/MalformedURLException.php');

class SQLReader implements Reader {
    
    public function isValid($parameters) {
        $parameterSplit = trim($parameters['parameters'])==''?array():explode('/', $parameters['parameters']);
        
        if(count($parameterSplit)%2 != 0) {
            throw new MalformedURLException('Odd number of parameters specified.');
        }
        
        return true;
    }
    
    public function execute($sql) {
        // Connect to the database
        $connection = new Connection();
        $connection->connect(Config::$DB_HOST, Config::$DB_USER, Config::$DB_PASSWORD);
        $connection->selectDatabase(Config::$DB);
        
        
    }
}
?>