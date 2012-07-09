<?php
require_once('JSONPrinter.php');

/**
 * Simple factory pattern that creates the printer.
 * 
 * @package TakeALookInside/models
 * @author Sam Verschueren  <sam@irail.be>
 */
class PrinterFactory {
    
    /**
     * Returns the Printer.
     * 
     * @param   type    $type   The type of the printer
     */
    public static function createPrinter($type='') {
        $type = trim($type);
        if($type=='') {
            $type = 'JSON';
        }
        
        $type .= 'Printer';     
            
        return new $type;
    }
}
?>