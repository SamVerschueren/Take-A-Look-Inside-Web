<?php
class Movie {
    
    private $id;
    private $file;
    private $qrToken;
    private $building;
    private $dateTime;
    
    public function __construct($file, $qrToken, DateTime $dateTime=null) {
        $this->setFile($file);
        $this->setQrToken($qrToken);
        $this->setDateTime($dateTime);
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }
    
    private function setFile($file) {
        $this->file = $file;
    }
    
    public function getFile() {
        return $this->file;
    }
    
    private function setDateTime(DateTime $dateTime=null) {
        if($dateTime == null) {
            $dateTime = new DateTime();
        }
        
        $this->dateTime = $dateTime;
    }
    
    public function getDateTime() {
        return $this->dateTime;
    }
    
    private function setQrToken($qrToken) {
        $this->qrToken = $qrToken;
    }
    
    public function getQrToken() {
        return $this->qrToken;
    }
}
?>