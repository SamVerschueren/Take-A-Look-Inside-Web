<?php
include_once '(Printer.php)';

/**
 * Implementation of a printer that prints in the JSON format.
 *
 * @package TakeALookInside/models
 * @author Lieven Benoot  <lieven.benoot@irail.be>
 */
class JSONPrinter extends Printer{
    
    /**
     * Prints $toPrint in JSON format.
     */
    public function doPrint($toPrint){
        echo "print in JSON format";    
    }
}

?>