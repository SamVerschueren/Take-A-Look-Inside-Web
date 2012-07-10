<?php
/**
 * Exception when Operation is not (yet) supported.
 * 
 * @package TakeALookInside
 * @author Sam Verschueren  <sam@irail.be>
 */
class UnsupportedOperationException extends RuntimeException {
    
    public function __construct($message, $code=0, Exception $previous=null) {
        parent::__construct($message, $code, $previous);
    }
    
    public function __toString() {
        return parent::getMessage();
    }
}
?>