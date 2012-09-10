<?php
/**
 * Exception when can't connect with the database.
 * 
 * @package exceptions
 * @since 2012-07-11
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class SQLException extends Exception {
    
    public function __construct($message, $code=0, Exception $previous=null) {
        parent::__construct($message, $code, $previous);
    }
}
?>