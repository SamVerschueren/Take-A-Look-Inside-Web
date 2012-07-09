<?php
require_once('Controller.php');
require_once('/models/SQLReader.php');

/**
 * Buildingcontroller
 *
 * @package TakeALookInside/controllers
 * @author Sam Verschueren  <sam@irail.be>
 */
class BuildingController extends Controller {
    
    /**
     * Getting the information of a building.
     * 
     * @param   parameters  $parameters The parameters of the url.
     */
    public function get($parameters) {
        $outputFormat = $parameters['format'];
        
        if(!isset($_GET['dataFormat'])) {
            $reader = 'SQLReader';
        }
        else {
            $reader = strtoupper(trim($_GET['dataFormat'])) . 'Reader';
        }
        
        $readerClass = new $reader;
        
        if(!$readerClass->isValid($parameters)) {
            throw new InvalidArgumentException('URL parameters are not valid');
        }
        
        $readerClass->read($parameters);
        
        /*$resource = $parameters['resource'];
        
        $get = $_GET;
        $get['parameters'] = $parameters['parameters'];
        
        $this->persistenceController->$resource($get);*/
    }
}
?>