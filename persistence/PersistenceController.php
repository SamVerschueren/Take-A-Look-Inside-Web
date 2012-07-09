<?php
require_once('Connection.php');

class PersistenceController {
    
    private static $instance;
    
    private function __construct() {
        
    }
    
    public static function getInstance() {
        if(!isset(self::$instance)) {
            self::$instance = new PersistenceController();
        }
        
        return self::$instance;
    }
    
    public function users(array $parameters) {
        print_r($parameters);
    }
}
?>