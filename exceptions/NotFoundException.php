<?php
/**
 * Exception when something could not be found.
 * 
 * @package TakeALookInside
 * @author Sam Verschueren  <sam@irail.be>
 */
class NotFoundException extends Exception {
    
    public function __construct($message, $code=0, Exception $previous=null) {
        parent::__construct($message, $code, $previous);
    }
    
    public function __toString() {
        return parent::getMessage();
    }
}
?>