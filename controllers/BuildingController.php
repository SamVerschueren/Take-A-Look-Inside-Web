<?php
require_once('system/web/mvc/JsonRequestBehaviour.php');
require_once('system/web/mvc/Controller.php');

require_once('models/DAL/BuildingMapper.php');

class BuildingController extends Controller {

    private $buildingMapper;

    public function __construct() {
        parent::__construct();
        
        $this->buildingMapper = new BuildingMapper();
    }
    
    public function index($id=null, $token=null) {
        if($id == null) {
            if($token == null) {
                $buildings = $this->buildingMapper->findAllObjects();
            }
            else {
                $buildings = $this->buildingMapper->findByQrToken($token);
            }
        }
        else {
            $buildings = $this->buildingMapper->findByUniqueId($id);
        }
        
        $result = array();
        
        if(is_array($buildings)) {
            foreach($buildings as $building) {
                $result[] = $this->objectToArray($building);
            }
        }
        else {
            $result = $this->objectToArray($buildings);    
        }
        
        $json = $this->json($result);
        $json->setJsonRequestBehaviour(JsonRequestBehaviour::AllowGet);
        
        return $json;
    }
    
    public function top($id) {
        $buildings = $this->buildingMapper->findAllObjects();
        
        $slice = array_slice($buildings, 0, $id);
        
        $result = array();
        foreach($slice as $building) {
            $result[] = $this->objectToArray($building);
        }
        
        $json = $this->json($result);
        $json->setJsonRequestBehaviour(JsonRequestBehaviour::AllowGet);
        
        return $json;
    }
    
    public function category($id) {
        $buildings = $this->buildingMapper->findByCategoryId($id);
        
        $result = array();
        
        foreach($buildings as $building) {
            $result[] = $this->objectToArray($building);
        }
        
        $json = $this->json($result);
        $json->setJsonRequestBehaviour(JsonRequestBehaviour::AllowGet);
        
        return $json;
    }
    
    public function favorite() {
        if($_SERVER['REQUEST_METHOD'] == 'post') {
            if($_POST['method'] == 'like') {
                
            }
            else {
                
            }
        }
        
        exit();
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