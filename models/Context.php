<?php

require_once('Printer.php');

/**
 * Interface that tells which methods that should be implemented.
 *
 * @package TakeALookInside/models
 * @author Lieven Benoot  <lieven.benoot@irail.be>
 */
class Context {	
	
    /**
     * Create Context for specific printer.
     * 
     * @param   printer     $p  Specific type of printer to use.
     */
	public function __construct(Printer $p){
		$this->printer=$p;		
	}
    
    /**
     * Use the print function of the printer.
     * 
     * @param   toPrint     $toPrint   Data to print with selected printer. 
     */
    public function executePrint($toPrint){
        $this->$printer.print($toPrint);
    }
}
?>