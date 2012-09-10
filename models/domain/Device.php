<?php
class Device {
    
    private $id;
    private $device;
    private $mustSees = array();
    
    public function __construct($device) {
        $this->setDevice($device);
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }
    
    private function setDevice($device) {
        $this->device = $device;
    }
    
    public function getDevice() {
        return $this->device;
    }
    
    public function addMustSee(Building $building) {
        $this->mustSees[] = $building;
    }
    
    public function getMustSees() {
        return $this->mustSees;
    }
}
?>