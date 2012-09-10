<?php
class Location {
    
    private $longitude;
    private $latitude;
    private $adress;
    
    public function __construct($longitude, $latitude, $adress) {
        $this->setLongitude($longitude);
        $this->setLatitude($latitude);
        $this->setAdress($adress);
    }
    
    private function setLongitude($longitude) {
        $this->longitude = $longitude;
    }
    
    public function getLongitude() {
        return $this->longitude;
    }
    
    private function setLatitude($latitude) {
        $this->latitude = $latitude;
    }
    
    public function getLatitude() {
        return $this->latitude;
    }
    
    private function setAdress($adress) {
        $this->adress = $adress;
    }
    
    public function getAdress() {
        return $this->adress;
    }

    public function __toString() {
        return $this->adress;
    }
}
?>