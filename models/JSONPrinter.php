<?php
include_once('Printer.php');

/**
 * Implementation of a printer that prints in the JSON format.
 *
 * @package TakeALookInside/models
 * @author Lieven Benoot  <lieven.benoot@irail.be>
 */
class JSONPrinter extends Printer{
    
    /**
     * Print in JSON.
     *
     * @param   toPrint     $toPrint    The object to print in JSON.
     */
    public function doPrint($toPrint){
        echo "print in JSON format";    
    }
}

?>