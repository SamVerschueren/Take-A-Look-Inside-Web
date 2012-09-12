<?php
class User {
    
    private $id;
    private $name;
    private $role;
    private $ip;
    
    public function __construct($name, $role, $ip=null) {
        $this->setName($name);
        $this->setRole($role);
        $this->setIp($ip);
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
    
    private function setRole($role) {
        $this->role = $role;
    }
    
    public function getRole() {
        return $this->role;
    }
    
    private function setIp($ip=null) {
        if($ip == null) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        $this->ip = $ip;
    }
    
    public function getIp() {
        return $this->ip;
    }
    
    public function __toString() {
        return $this->name;
    }
}
?>