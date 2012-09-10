<?php
require_once('modelconfiguration/Entity.php');

/**
 * DbModelBuilder is used to map CLR classes to a database schema.
 * 
 * @package system.data.entity
 * @since 2012-07-28
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class DbModelBuilder {
    
    private $configurations = array();
    
    public function getConfigurations() {
        return $this->configurations;
    }
    
    public function entity($entityName) {
        $entity = new Entity($entityName);
        
        $this->configurations[] = $entity;
        
        return $entity;
    }
}
?>