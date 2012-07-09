<?php 

/**
 * Interface that all printers should implement.
 *
 * @package TakeALookInside/models
 * @author Lieven Benoot  <lieven.benoot@irail.be>
 */
interface Printer{    
	public function doPrint($toPrint);	
}

?>