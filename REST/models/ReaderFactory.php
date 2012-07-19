<?php
require_once('SQLReader.php');

/**
 * Simple factory pattern that creates the reader.
 * 
 * @package TakeALookInside/models
 * @author Sam Verschueren  <sam@irail.be>
 */
class ReaderFactory {
    
    /**
     * Returns the Reader.
     * 
     * @param   type    $type   The type of the reader
     * @return  type    return reader of the specific type
     */
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