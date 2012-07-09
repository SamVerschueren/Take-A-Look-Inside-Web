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
        
        $where = '';
        
        foreach(parent::splitParameters($parameters['parameters']) as $key => $value) {
            if($key != 'dataFormat') {
                if($where == '') {
                    $where = 'WHERE ' . mysql_real_escape_string($key) . '=\'' . mysql_real_escape_string($value) . '\'';
                }
                else {
                    $where .= ' AND ' . mysql_real_escape_string($key) . '=\'' . mysql_real_escape_string($value) . '\'';
                }
            }
        }
        
        $sql = 'SELECT * FROM Category ';
        $sql .= $where;
        
        $reader->execute($sql);
    }
}
?>