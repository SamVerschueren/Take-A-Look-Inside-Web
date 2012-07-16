<?php
include_once('IPrinter.php');

/**
 * Implementation of a printer that prints in the 3GP video format.
 *
 * @package TakeALookInside/models
 * @author Sam Verschueren  <sam@irail.be>
 */
class GP3Printer implements IPrinter{
    
    /**
     * Print in 3GP.
     *
     * @param   data    $data   The array object to print in JSON.
     */
    public function doPrint(array $data) {
        print_r($data);
    }
}
?>
