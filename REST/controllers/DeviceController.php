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
        $deviceAlreadyInDb=parent::devicePresentInDb($_POST['device']);       
           
        if(!$deviceAlreadyInDb){
            $sqlInsert="INSERT INTO device (device) VALUES ('". mysql_real_escape_string($_POST['device'])."')";
            mysql_query($sqlInsert);
            
            echo "inserted";
        } 
        else {
            echo "already exists";
        }
    }     
}
?>