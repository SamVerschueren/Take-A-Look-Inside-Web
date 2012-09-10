<?php
require_once('system/data/common/DbConnection.php');

require_once('models/domain/Message.php');

/**
 * Represents a combination of the Unit-Of-Work and Repository patterns and enables you to query a database and group together changes that will then be written back to the store as a unit.
 * 
 * @package system.data.entity
 * @since 2012-07-28
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
abstract class DbContext {
 
    private $dbConnection;
 
    public function __construct($database) {
        $dbConnection = new DbConnection(Config::$DB_HOST, Config::$DB_USER, Config::$DB_PASSWORD);
        $dbConnection->connect();
        $dbConnection->selectDatabase($database);
        
        $this->dbConnection = $dbConnection;
    }
    
    public function execute(DbModelBuilder $modelBuilder) {
        foreach($modelBuilder->getConfigurations() as $configuration) {
            $reflectionClass = new ReflectionClass($this);
            
            $entity = $configuration->getEntity();
            
            $entityObject = new $entity;
            
            $property = $reflectionClass->getProperty(strtolower($configuration->getEntity()));
            $property->setValue($this, $entityObject);
        }
        
        echo $this->message;
    }
    
    public abstract function onModelCreating(DbModelBuilder $dbModelBuilder);
}
?>