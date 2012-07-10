<?php
require_once('NotFoundException.php');

/**
 * Exception when Class could not be found.
 * 
 * @package TakeALookInside
 * @author Sam Verschueren  <sam@irail.be>
 */
class ClassNotFoundException extends NotFoundException {
    
    public function __construct($message, $code=0, Exception $previous=null) {
        parent::__construct($message, $code, $previous);
    }
}
?>