<?php
require_once('system/data/entity/IMapper.php');

/**
 * A Mapper is responsible for mapping domain objects to a persistent data store. By removing this functionality from the domain objects, 
 * the backend datastore implementation is allowed to change without affecting any domain objects. 
 * 
 * @package models.DAL
 * @since 2012-08-01
 * @author Sam Verschueren  <sam@histranger.be>
 */
abstract class Mapper implements IMapper {
    /**
     * Create the given object in a persistent data store.
     * 
     * @param   object      The object that should be created in the database.
     * @throws  exception   InvalidArgumentException if parameter is not an object.
     */
    public function create($object) {
        throw new UnsupportedOperationException('Can not create a new object.');
    }
    
    /**
     * Remove an object from the data store.
     * 
     * @param   object      The object that should be deleted in the database.
     * @throws  exception   UnsupportedOperationException if method is not overriden
     */
    public function delete($object) {
        if(!is_object($object)) {
            throw new InvalidArgumentException('Given parameter is not an object.');
        }
        
        $tableName = strtolower(get_class($object));
        
        mysql_query("DELETE FROM " . mysql_real_escape_string($tableName) . " WHERE id='" . mysql_real_escape_string($object->getId()) . "'") or die(mysql_error());
    }
    
    /**
     * Returns a Collection of all objects for the given mapper.
     * 
     * @return  objects     Array of all the objects.
     * @throws  exception   UnsupportedOperationException if method is not overriden
     */
    public function findAllObjects() {
        throw new UnsupportedOperationException('Can not find all the objects.');
    }
    
    /**
     * Find the given Object based on its unique identifier.
     * 
     * @param   id          The one that represents the unique ID.
     * @return  object      A single object with the given id or null if none found.
     * @throws  exception   UnsupportedOperationException if method is not overriden
     */
    public function findByUniqueId($id) {
        throw new UnsupportedOperationException('Can find an object by id.');
    }
    
    /**
     * Update the given object in the data store.
     * 
     * @param   object      The object that should be updated.
     * @throws  exception   UnsupportedOperationException if method is not overriden
     */
    public function update($object) {
        throw new UnsupportedOperationException('Can not update an object.');
    }
}
?>