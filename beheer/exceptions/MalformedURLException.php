<?php
require_once('NotFoundException.php');

/**
 * Exception when URL is malformed.
 * 
 * @package TakeALookInside
 * @author Sam Verschueren  <sam@irail.be>
 */
class MalformedURLException extends Exception {
    
    public function __construct($message, $code=0, Exception $previous=null) {
        parent::__construct($message, $code, $previous);
    }
}
?>