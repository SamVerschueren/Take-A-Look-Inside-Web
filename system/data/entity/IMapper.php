<?php
/**
 * A Mapper is responsible for mapping domain objects to a persistent data store. By removing this functionality from the domain objects, 
 * the backend datastore implementation is allowed to change without affecting any domain objects. 
 * 
 * @package system.data.entity
 * @since 2012-08-01
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
interface IMapper {
    
    /**
     * Create the given object in a persistent data store.
     * 
     * @param   object      The object that should be created in the database.
     */
    public function create($object);
    
    /**
     * Remove an object from the data store.
     * 
     * @param   object      The object that should be deleted in the database.
     */
    public function delete($object);
    
    /**
     * Returns a Collection of all objects for the given mapper.
     * 
     * @return  objects     Array of all the objects.
     */
    public function findAllObjects();
    
    /**
     * Find the given Object based on its unique identifier.
     * 
     * @param   id          The one that represents the unique ID.
     * @return  object      A single object with the given id or null if none found.
     */
    public function findByUniqueId($id);
    
    /**
     * Update the given object in the data store.
     * 
     * @param   object      The object that should be updated.
     */
    public function update($object);
}
?>