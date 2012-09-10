<?php
require_once('system/web/mvc/JsonRequestBehaviour.php');
require_once('system/web/mvc/Controller.php');

require_once('models/DAL/CategoryMapper.php');

class CategoryController extends Controller {

    private $categoryMapper;

    public function __construct() {
        parent::__construct();
        
        $this->categoryMapper = new CategoryMapper();
    }
    
    public function index($id=null) {
        if($id == null) {
            $categories = $this->categoryMapper->findAllObjects();
        }
        else {
            $categories = $this->categoryMapper->findByUniqueId($id);
        }
        
        $result = array();
        
        if(is_array($categories)) {
            foreach($categories as $category) {
                $result[] = $this->objectToArray($category);
            }
        }
        else {
            $result = $this->objectToArray($categories);    
        }
        
        $json = $this->json($result);
        $json->setJsonRequestBehaviour(JsonRequestBehaviour::AllowGet);
        
        return $json;
    }

    private function objectToArray($object) {
        if(!is_object($object)) {
            // throw exception
        }
        
        $reflectionClass = new ReflectionClass($object);
        $properties = $reflectionClass->getProperties();
        
        $array = array();
        
        foreach($properties as $property) {
            $reflectionProperty = new ReflectionProperty($object, $property->getName());
            $reflectionProperty->setAccessible(true);
            
            $value = $reflectionProperty->getValue($object);
            
            if(!is_object($value)) {
                $array[$property->getName()] = $value;
            }
            else if($value instanceof DateTime) {
                $array['timestamp'] = $value->getTimestamp();
            }
            else {
                $array[$property->getName()] = $this->objectToArray($value);
            }
        }
        
        return $array;
    }
}
?>