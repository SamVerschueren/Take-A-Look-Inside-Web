<?php
require_once('Controller.php');

/**
 * DeviceController
 *
 * @package TakeALookInside/controllers
 * @author Lieven Benoot  <lieven.benoot@irail.be>
 */
class DeviceController extends Controller {

    
    public function get($parameters) {
        $isPresent = parent::devicePresentInDb($_GET['device']);
        $result = array('exists' => $isPresent);
        
        $outputFormat = $parameters['format'];
        
        $printer = PrinterFactory::createPrinter($outputFormat);
        $printer->doPrint($result);
    }
    
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