<?php
/**
 * Signals that an I/O exception of some sort has occurred. This class is the general class of exceptions produced by failed or interrupted I/O operations. 
 * 
 * @package exceptions.runtime
 * @since 2012-09-10
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class IOException extends Exception {
    
    public function __construct($message, $code=0, Exception $previous=null) {
        parent::__construct($message, $code, $previous);
    }
    
    public function __toString() {
        return parent::getMessage();
    }
}
?>