<?php
/**
 * Exception when something could not be found.
 * 
 * @package exceptions
 * @since 2012-07-11
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class NotFoundException extends Exception {
    
    public function __construct($message, $code=0, Exception $previous=null) {
        parent::__construct($message, $code, $previous);
    }
}
?>