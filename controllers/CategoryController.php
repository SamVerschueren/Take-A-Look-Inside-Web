<?php
require_once('Controller.php');
require_once('models/SQLReader.php');
require_once('models/ReaderFactory.php');

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
     * @param   parameters  $parameters The parameters of the url.
     */
    public function get($parameters) {
        $dataFormat = isset($_GET['dataFormat'])?$_GET['dataFormat']:'';
        $reader = ReaderFactory::createReader($dataFormat);
                
        $outputFormat = $parameters['format'];
        
        if(!$reader->isValid($parameters)) {
            throw new InvalidArgumentException('URL parameters are not valid');
        }
        
        $resource = 'Category';
        $restrictions = parent::splitParameters($parameters['parameters']);
        
        $reader->execute($resource, $restrictions);
    }
}
?>