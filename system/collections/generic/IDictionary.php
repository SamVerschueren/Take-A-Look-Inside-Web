<?php
require_once('ICollection.php');
require_once('KeyValuePair.php');

/**
 * Represents a generic collection of key/value pairs.
 * 
 * @package system.collections.generic
 * @since 2012-06-29
 * @author Sam Verschueren
 */
interface IDictionary extends ICollection {
    /**
     * Sets the element with the specified key.
     * 
     * @param   key     The key of the element to set.
     * @param   item    The item that should be set.
     */
    public function setItem($key, $item);
    
    /**
     * Gets the element with the specified key.
     * 
     * @param   key     The key of the element to get.
     * @return  item    The item that should be returned.
     */
    public function getItem($key);
    
    /**
     * Gets an array containing the keys in the IDictionary.
     * 
     * @return  array   An array containing the keys in the object that implements IDictionary.
     */
    public function getKeys();
    
    /**
     * Gets an array containing the values in the IDictionary.
     * 
     * @return  array   An array containing the values in the object that implements IDictionary.
     */
    public function getValues();
    
    /**
     * Determines whether the IDictionary contains an element with the specified key.
     * 
     * @param   key     The key to locate in the IDictionary.
     */
    public function containsKey($key);
    
    /**
     * Gets the value associated with the specified key.
     * 
     * @param   key     The key whose value to get.
     * @param   value   When this method returns, the value associated with the specified key, if the key is found; otherwise, the default value for the type of the value parameter. This parameter is passed uninitialized.
     * @return  boolean true if the object that implements IDictionary contains an element with the specified key; otherwise, false.
     */
    public function tryGetValue($key, &$value);
}
?>