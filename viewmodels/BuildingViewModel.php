<?php
require_once('models/domain/Building.php');

class BuildingViewModel {
    
    private $id;
    private $name;
    private $category;
    private $adress;
    private $longitude;
    private $latitude;
    private $movie;
    private $token;
    private $description;
    
    public function __construct(Building $building) {
        $this->setId($building->getId());
        $this->setName($building->getName());
        $this->setCategory($building->getCategory()->getName());
        $this->setAdress($building->getLocation()->getAdress());
        $this->setLongitude($building->getLocation()->getLongitude());
        $this->setLatitude($building->getLocation()->getLatitude());
        if($building->getMovie() != null) {
            $this->setMovie($building->getMovie()->getFile());
            $this->setToken($building->getMovie()->getQrToken());    
        }
        $this->setDescription($building->getDescription());
    }
    
    private function setId($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }
    
    private function setName($name) {
        $this->name = $name;;
    }
    
    public function getName() {
        return $this->name;
    }
    
    private function setCategory($category) {
        $this->category = $category;
    }
    
    public function getCategory() {
        return $this->category;
    }
    
    private function setAdress($adress) {
        $this->adress = $adress;
    }
    
    public function getAdress() {
        return $this->adress;
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
    
    private function setMovie($movie) {
        $this->movie = $movie;
    }
    
    public function getMovie() {
        return $this->movie;
    }
    
    private function setToken($token) {
        $this->token = $token;
    }
    
    public function getToken() {
        return $this->token;
    }
    
    private function setDescription($description) {
        $this->description = $description;
    }
    
    public function getDescription() {
        return $this->description;
    }
}
?>