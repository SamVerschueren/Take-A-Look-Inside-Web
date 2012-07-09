<?php
require_once('SQLReader.php');

class ReaderFactory {
    
    public static function createReader($type='') {
        $type = trim($type);
        if($type=='') {
            $type = 'SQL';
        }
        
        $type .= 'Reader';     
            
        return new $type;
    }
}
?>