<?php
include_once('IPrinter.php');

/**
 * Implementation of a printer that prints in the JSON format.
 *
 * @package TakeALookInside/models
 * @author Sam Verschueren  <sam@irail.be>
 */
class XMLPrinter implements IPrinter{
    
    /**
     * Print in JSON.
     *
     * @param   toPrint     $toPrint    The query object to print in XML.
     */
    public function doPrint(array $toPrint) {
        header('Content-type: application/xml');
        
        print_r($toPrint);
    }
}
?>