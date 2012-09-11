<?php
require_once('models/domain/Device.php');

require_once('Mapper.php');
require_once('BuildingMapper.php');

/**
 * This class maps every SQL device to a PHP device
 * 
 * @package models.DAL
 * @since 2012-09-07
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class DeviceMapper extends Mapper {
    
    private $buildingMapper;
    
    public function __construct() {
        $this->buildingMapper = new BuildingMapper();
    }
    
    /**
     * Create the given object in a persistent data store.
     * 
     * @param   object      The object that should be created in the database.
     * @throws  exception   InvalidArgumentException if parameter is not an object.
     */
    public function create($object) {
        if(!($object instanceof Device)) {
            throw new ClassCastException("Object passed trough is not an instance of Device");
        }
        
        mysql_query("INSERT INTO device(device) VALUES('" . mysql_real_escape_string($object->getDevice()) . "')");
        
        $id = mysql_insert_id();
        
        $object->setId($id);
    }
    
    /**
     * Returns a Collection of all objects for the given mapper.
     * 
     * @return  objects     Array of all the objects.
     * @throws  exception   UnsupportedOperationException if method is not overriden
     */
    public function findAllObjects() {
        $resultset = mysql_query("SELECT id, device FROM device");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the devices.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No devices where found.');
        }
        
        $result = array();
        
        while($data = mysql_fetch_assoc($resultset)) {
            $device = new Device($data['device']);
            $device->setId($data['id']);
            
            $mustSeeResultset = mysql_query("SELECT buildingID FROM must_sees WHERE deviceID='" . mysql_real_escape_string($device->getId()) . "'");
            
            while($mustSeeData = mysql_fetch_assoc($mustSeeResultset)) {
                $building = $this->buildingMapper->findByUniqueId($mustSeeData['buildingID']);
                
                $device->addMustSee($building);    
            }
            
            $result[] = $device;
        }
        
        return $result;
    }
    
    /**
     * Find the given Object based on its unique identifier.
     * 
     * @param   id          The one that represents the unique ID.
     * @return  object      A single object with the given id or null if none found.
     * @throws  exception   UnsupportedOperationException if method is not overriden
     */
    public function findByUniqueId($id) {
        $resultset = mysql_query("SELECT id, device FROM device WHERE id='" . mysql_real_escape_string($id) . "'");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the device with id ' . $id . '.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No devices found with id ' . $id . '.');
        }
        
        $data = mysql_fetch_assoc($resultset);
        
        $device = new Device($data['device']);
        $device->setId($data['id']);
        
        $mustSeeResultset = mysql_query("SELECT buildingID FROM must_sees WHERE deviceID='" . mysql_real_escape_string($device->getId()) . "'");
            
        while($mustSeeData = mysql_fetch_assoc($mustSeeResultset)) {
            $building = $this->buildingMapper->findByUniqueId($mustSeeData['buildingID']);
            
            $device->addMustSee($building);    
        }
        
        return $device;
    }

    public function findByDeviceId($id) {
        $resultset = mysql_query("SELECT id, device FROM device WHERE device='" . mysql_real_escape_string($id) . "'");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the device with deviceid ' . $id . '.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No devices found with deviceid ' . $id . '.');
        }
        
        $data = mysql_fetch_assoc($resultset);
        
        $device = new Device($data['device']);
        $device->setId($data['id']);
        
        $mustSeeResultset = mysql_query("SELECT buildingID FROM must_sees WHERE deviceID='" . mysql_real_escape_string($device->getId()) . "'");
            
        while($mustSeeData = mysql_fetch_assoc($mustSeeResultset)) {
            $building = $this->buildingMapper->findByUniqueId($mustSeeData['buildingID']);
            
            $device->addMustSee($building);    
        }
        
        return $device;
    }
    
    /**
     * Update the given object in the data store.
     * 
     * @param   object      The object that should be updated.
     * @throws  exception   UnsupportedOperationException if method is not overriden
     */
    public function update($object) {
        if(!($object instanceof Device)) {
            throw new ClassCastException("Could not cast this class to Device.");
        }
        
        mysql_query("DELETE FROM must_sees WHERE deviceID='" . mysql_real_escape_string($object->getId()) . "'");
        
        $string = '';
        
        foreach($object->getMustSees() as $building) {
            $string .= '(' . mysql_real_escape_string($building->getId()) .  ', ' . mysql_real_escape_string($object->getId()) . '),';
        }
        
        if($string != '') {
            $string = trim($string, ',');
            
            mysql_query("INSERT INTO must_sees(buildingID, deviceID) VALUES " . $string);
        }
    }
}
?>