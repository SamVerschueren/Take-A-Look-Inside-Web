<?php
require_once('system/web/mvc/JsonRequestBehaviour.php');
require_once('system/web/mvc/Controller.php');

require_once('models/DAL/BuildingMapper.php');
require_once('models/DAL/DeviceMapper.php');

/**
 * This controller handles every action that has something to do with the building.
 * 
 * @package controllers
 * @since 2012-09-8
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class BuildingController extends Controller {

    private $buildingMapper;
    private $deviceMapper;

    public function __construct() {
        parent::__construct();
        
        $this->buildingMapper = new BuildingMapper();
        $this->deviceMapper = new DeviceMapper();
    }
    
    /**
     * The index method returns a json string of a building or of all the buildings.
     * 
     * @param   id      If the id is set, this method will return a json string of the building with that id.
     * @param   token   If the token is set, this method will return the building that belongs to the QRToken.
     * @return  json    A JsonResult that can be executed and that will display the json.
     */
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
    
    /**
     * This method returns the top elements of the buildings.
     * 
     * @param   id      It returns the top number of elements specified by this number.
     * @return  json    A JsonResult that can be executed and that will display the json.
     */
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
    
    /**
     * This method returns every building with the categoryid equals to the specified id.
     * 
     * @param   id      The categoryID.
     * @return  json    A JsonResult that can be executed and that will display the json.
     */
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
    
    /**
     * This method can be used to favorite a building.
     * 
     * @param   method          This is like or unlike. If it's like, the data will be added to the database, otherwhise it will be removed.
     */
    public function favorite($method) {        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $device = $this->deviceMapper->findByDeviceId($_POST['device']);
                $building = $this->buildingMapper->findByUniqueId($_POST['id']);
                
                if($method == 'like') {
                    $device->addMustSee($building);
                }
                else {
                    $device->removeMustSee($building);
                }
                
                $this->deviceMapper->update($device);
            }
            catch(SQLException $ex) {
                // Failed to retreive the device or the building
                echo $ex->getMessage();
            }
        }
        
        exit();
    }

    /**
     * This method returns an array of an object. It uses reflection to retrieve all the properties.
     * 
     * @param   object      The object that should be transformed.
     * @return  array       The array with key-value pairs depending on the object.
     */    
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