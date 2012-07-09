<?php

require_once('Printer.php');

/**
 * Interface that tells which methods that should be implemented.
 *
 * @package TakeALookInside/controllers
 * @author Lieven Benoot  <lieven.benoot@irail.be>
 */
class Context {	
	
	public function __construct(Printer $p){
		$this->printer=$p;		
	}
    
    public function executePrint($toPrint){
        $this->$printer.print($toPrint);
    }
}
?>