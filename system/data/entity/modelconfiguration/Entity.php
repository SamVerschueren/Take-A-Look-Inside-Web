<?php
class Entity {
    
    private $entity;
    private $configuration = array();
    
    public function __construct($entity) {
        $this->entity = $entity;    
    }
    
    public function getEntity() {
        return $this->entity;
    }
    
    public function getConfiguration() {
        return $this->configuration;
    }
    
    public function hasKey($property) {
        $this->configuration['key'] = $property;
        
        return $this;
    }
}
?>