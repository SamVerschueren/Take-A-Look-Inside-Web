<?php
require_once('Controller.php');
require_once('models/SQLReader.php');
require_once('models/ReaderFactory.php');
require_once('models/PrinterFactory.php');

/**
 * The Controller that handles the Category
 *
 * @package TakeALookInside/controllers
 * @author Sam Verschueren  <sam@irail.be>
 */
class CategoryController extends Controller {
    
    /**
     * Getting the information of a category.
     * 
     * @param   array   $parameters The parameters of the url.
     * @return void
     */
    public function get($parameters) {
        $dataFormat = isset($_GET['dataFormat'])?$_GET['dataFormat']:'';
        $outputFormat = $parameters['format'];
        
        $reader = ReaderFactory::createReader($dataFormat);
        $printer = PrinterFactory::createPrinter($outputFormat);
        
        if(!$reader->isValid($parameters)) {
            throw new InvalidArgumentException('URL parameters are not valid');
        }
        
        $resource = 'category';
        $restrictions = parent::splitParameters($parameters['parameters']);
        
        $printer->doPrint($reader->execute($resource, $restrictions));
    }
}
?>