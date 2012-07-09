<?php
require_once('Controller.php');
require_once('models/ReaderFactory.php');

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
        $dataFormat = isset($_GET['dataFormat'])?$_GET['dataFormat']:'';
        $reader = ReaderFactory::createReader($dataFormat);
                
        $outputFormat = $parameters['format'];
        
        if(!$reader->isValid($parameters)) {
            throw new InvalidArgumentException('URL parameters are not valid');
        }
        
        $reader->read($parameters);
    }
}
?>