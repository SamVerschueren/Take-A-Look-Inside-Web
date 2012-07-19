<?php
require_once('Controller.php');

/**
 * DeviceController
 *
 * @package TakeALookInside/controllers
 * @author Lieven Benoot  <lieven.benoot@irail.be>
 */
class DeviceController extends Controller {

    /**
     * Gets a array which returns whether a specific device exists in the database or not.
     * Example in JSON format: Result is returned like this: { "exists":"true" } or { "exists":"false" }
     */
    public function get($parameters) {
        $isPresent = parent::devicePresentInDb($_GET['device']);
        $result = array('exists' => $isPresent);
        
        $outputFormat = $parameters['format'];
        
        $printer = PrinterFactory::createPrinter($outputFormat);
        $printer->doPrint($result);
    }
    
    /**
     * Posts method that checks if the device is already present in the DB. 
     * Inserts the device of it is not yet present.
     * 
     */
    public function post($parameters){
        $deviceAlreadyInDb=parent::deviceAlreadyInDb($_GET['device']);       
        
        if(!$deviceAlreadyInDb){
            $sqlInsert="INSERT INTO device (device) VALUES ('". mysql_real_escape_string($device)."')";
            mysql_query($sqlInsert);
            echo "Device inserted";            
        } 
        else echo "Device already present in DB";
        
    }     
}
?>