<?php
require_once('NotFoundException.php');

/**
 * Exception when File could not be found.
 * 
 * @package exceptions
 * @since 2012-07-29
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class FileNotFoundException extends NotFoundException {
    
    public function __construct($message, $code=0, Exception $previous=null) {
        parent::__construct($message, $code, $previous);
    }
}
?>