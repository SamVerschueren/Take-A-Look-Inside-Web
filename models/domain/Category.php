<?php
class Category {
    
    private $id;
    private $name;
    
    public function __construct($name) {
        $this->setName($name);
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }
    
    private function setName($name) {
        $this->name = $name;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function __toString() {
        return $this->name;
    }
}
?>