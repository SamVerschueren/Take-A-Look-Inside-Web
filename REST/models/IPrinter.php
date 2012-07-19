<?php 

/**
 * Interface that all printers should implement.
 *
 * @package TakeALookInside/models
 * @author Lieven Benoot  <lieven.benoot@irail.be>
 */
interface IPrinter{
    /**
     * Execute print of the printer. 
     * Prints in specific format.
     * 
     * @param   $data   array   Data to print in specific format.     
     * @return  void
     */    
	public function doPrint(array $data);	
}
?>