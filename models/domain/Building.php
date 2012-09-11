<?php
class Building {
    
    private $id;
    private $name;
    private $description;
    private $infoLink;
    private $mustSee;
    private $category;
    private $movie;
    private $location;
    
    public function __construct($name, $description, $infoLink, $mustSee, Location $location, Category $category, Movie $movie=null) {
        $this->setName($name);
        $this->setDescription($description);
        $this->setInfoLink($infoLink);
        $this->setMustSee($mustSee);
        $this->setLocation($location);
        $this->setCategory($category);
        $this->setMovie($movie);
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setDescription($description) {
        $this->description = $description;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function setInfoLink($infoLink) {
        $this->infoLink = $infoLink;
    }
    
    public function getInfoLink() {
        return $this->infoLink;
    }
    
    public function setMustSee($mustSee) {
        $this->mustSee = $mustSee;
    }
    
    public function getMustSee() {
        return $this->mustSee;
    }
        
    public function setLocation(Location $location) {
        $this->location = $location;
    }
    
    public function getLocation() {
        return $this->location;
    }
    
    public function setCategory(Category $category) {
        $this->category = $category;
    }
    
    public function getCategory() {
        return $this->category;
    }
    
    public function setMovie(Movie $movie=null) {
        $this->movie = $movie;
    }
    
    public function getMovie() {
        return $this->movie;
    }
    
    public function __toString() {
        return $this->name;
    }
}
?>