<?php

include_once('IPrinter.php');
/**
 * Implementation of a printer that prints in the JSON format.
 *
 * @package TakeALookInside/models
 * @author Lieven Benoot  <lieven.benoot@irail.be>
 */
class JSONPrinter implements IPrinter{
    
    /**
     * Print in JSON.
     *
     * @param   toPrint     $toPrint    The querry object to print in JSON.
     */
    public function doPrint(array $toPrint) {
        header('Content-type: application/json');

        echo json_encode($toPrint);  
    }
}
?>
