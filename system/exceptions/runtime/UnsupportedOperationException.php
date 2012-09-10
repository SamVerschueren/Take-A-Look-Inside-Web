<?php
/**
 * Exception when Operation is not (yet) supported.
 * 
 * @package exceptions.runtime
 * @since 2012-07-11
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
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