<?php
require_once('models/domain/Movie.php');

class MovieViewModel {
    
    private $id;
    private $fileName;
    
    public function __construct(Movie $movie) {
        $this->setId($movie->getId());
        $this->setFileName($movie->getFile());
    }
    
    private function setId($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }
    
    private function setFileName($fileName) {
        $this->fileName = $fileName;
    }
    
    public function getFileName() {
        return $this->fileName;
    }
}
?>