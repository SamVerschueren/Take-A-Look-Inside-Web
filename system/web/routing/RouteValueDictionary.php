<?php
/**
 * Represents a case-insensitive collection of key/value pairs that you use in various places in the routing framework, such as when you 
 * define the default values for a route or when you generate a URL that is based on a route.
 * 
 * @package system.web.routing
 * @since 2012-07-29
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class RouteValueDictionary {
    
    private $dictionary = array();
    
    public function __construct($parameter=null) {
        if($parameter != null) {
            if(is_array($parameter)) {
                $this->dictionary = $parameter; 
            }
            else {
                $reflect = new ReflectionClass($parameter);
                $props = $reflect->getProperties(ReflectionProperty::IS_PRIVATE);
                
                foreach($props as $property) {
                    try {
                        $method = $reflect->getMethod('get' . ucfirst($property->getName()));
                        
                        $this->dictionary[$property->getName()] = $method->invoke($parameter);
                    }
                    catch(ReflectionException $ex) {
                        $this->dictionary[$property->getName()] = 'undefined';
                    }
                }
            }
        }
    }
    
    /**
     * Gets the number of elements contained in the ICollection.
     * 
     * @return  number  The number of elements contained in the ICollection.
     */
    public function count() {
        return count($this->dictionary);
    }
    
    /**
     * Gets the element with the specified key.
     * 
     * @param   key     The key of the element to get.
     * @return  item    The item that should be returned.
     */
    public function getItem($key) {
        if($this->containsKey($key)) {
            return $this->dictionary[$key]; 
        }
        
        return;
    }
    
    /**
     * Sets the element with the specified key.
     * 
     * @param   key     The key of the element to set.
     * @param   item    The item that should be set.
     */
    public function setItem($key, $value) {
        $this->dictionary[$key] = $value;
    }
    
    /**
     * Gets an array containing the keys in the IDictionary.
     * 
     * @return  array   An array containing the keys in the object that implements IDictionary.
     */
    public function getKeys() {
        return array_keys($this->dictionary);   
    }
    
    /**
     * Gets an array containing the values in the IDictionary.
     * 
     * @return  array   An array containing the values in the object that implements IDictionary.
     */
    public function getValues() {
        return array_values($this->dictionary); 
    }

    /**
     * Adds an item to the ICollection.
     * 
     * @param   item    The item that will be added to the collection.
     */
    public function add($key, $value) {
        $this->dictionary[$key] = $value;
    }
    
    /**
     * Removes all items from the ICollection.
     */
    public function clear() {
        $this->dictionary = array();
    }
    
    /**
     * Determines whether the IDictionary contains an element with the specified key.
     * 
     * @param   key     The key to locate in the IDictionary.
     */
    public function containsKey($key) {
        return array_key_exists($key, $this->dictionary);
    }
    
    
    /**
     * Determines whether the dictionary contains a specific value.
     * 
     * @param   key     The value to locate in the IDictionary.
     */
    public function containsValue($value) {
        return in_array($value, $this->dictionary); 
    }
    
    /**
     * Removes the value that has the specified key from the dictionary.
     * 
     * @param   key     The key of the element to remove in the dictionary.
     */
    public function remove($key) {
        unset($this->dictionary[$key]);
    }

    /**
     * Gets the value associated with the specified key.
     * 
     * @param   key     The key whose value to get.
     * @param   value   When this method returns, the value associated with the specified key, if the key is found; otherwise, the default value for the type of the value parameter. This parameter is passed uninitialized.
     * @return  boolean true if the object that implements IDictionary contains an element with the specified key; otherwise, false.
     */
    public function tryGetValue($key, &$value) {
        $value = $this->getItem($key);
        
        return $this->containsKey($key);
    }
}
?>