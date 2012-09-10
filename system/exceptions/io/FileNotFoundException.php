<?php
require_once('IOException.php');

/**
 * Signals that an attempt to open the file denoted by a specified pathname has failed.
 * 
 * @package exceptions.runtime
 * @since 2012-09-10
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class FileNotFoundException extends IOException {
    
    public function __construct($message, $code=0, Exception $previous=null) {
        parent::__construct($message, $code, $previous);
    }
    
    public function __toString() {
        return parent::getMessage();
    }
}
?>