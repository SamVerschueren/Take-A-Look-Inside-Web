<?php
/**
 * Exception when can't connect with the database.
 * 
 * @package TakeALookInside
 * @author Sam Verschueren  <sam@irail.be>
 */
class SQLException extends Exception {
    
    public function __construct($message, $code=0, Exception $previous=null) {
        parent::__construct($message, $code, $previous);
    }
}
?>