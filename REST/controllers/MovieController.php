<?php
require_once('Controller.php');
require_once('models/ReaderFactory.php');

/**
 * Buildingcontroller
 *
 * @package TakeALookInside/controllers
 * @author Sam Verschueren  <sam@irail.be>
 */
class MovieController extends Controller {
    
    /**
     * Getting the information of a building.
     * 
     * @param   parameters  $parameters The parameters of the url.
     */
    public function get($parameters) {
        $dataFormat = isset($_GET['dataFormat'])?$_GET['dataFormat']:'';
        $outputFormat = $parameters['format'];
        
        $reader = ReaderFactory::createReader($dataFormat);
        $printer = PrinterFactory::createPrinter($outputFormat);
        
        if(!$reader->isValid($parameters)) {
            throw new InvalidArgumentException('URL parameters are not valid');
        }
        
        $resource = 'movie';
        $restrictions = parent::splitParameters($parameters['parameters']);
         
        $data = $reader->execute($resource, $restrictions);
         
        $file = '../mov/' . $data['movie'][0]['movie']; 
        
        $movie = array();
        $movie['size'] = round(filesize($file)/1024, 2);
        $movie['token'] = $data['movie'][0]['qrID'];
        $movie['movie'] = 'http://tali.irail.be/mov/' . $data['movie'][0]['movie'];
        
        $printer->doPrint($movie);
    }
}
?>