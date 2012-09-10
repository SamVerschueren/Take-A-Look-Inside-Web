<?php
require_once('system/collections/generic/IDictionary.php');

/**
 * Represents a container that is used to pass data between a controller and a view. 
 *
 * @package system.web.mvc
 * @since 2012-06-29
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class ViewDataDictionary implements IDictionary, ArrayAccess {

    private $dictionary = array();
    private $model;
    
    public function __construct($parameter=null) {
        if($parameter != null) {
            if($parameter instanceof ViewDataDictionary) {
            
            }
            else {
                $reflect = new ReflectionClass($parameter);
                $props = $reflect->getProperties(ReflectionProperty::IS_PRIVATE);
                
                foreach($props as $property) {
                    try {
                        $method = $reflect->getMethod("get" . ucfirst($property->getName()));

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
     * Gets the number of elements in the collection.
     * 
     * @return  number  The number of elements in the collection.
     */
    public function count() {
        return count($this->dictionary);
    }

	/**
     * Gets a value that indicates whether the collection is read-only.
     * 
     * @return  boolean True if collection is read-only.
     */
    public function isReadOnly() {
        return false;
    }

    /**
     * Sets the element with the specified key.
     * 
     * @param   key     The key of the element to set.
     * @param   item    The item that should be set.
     */
    public function setItem($key, $item) {
        $this->dictionary[$key] = $value;
    }

    /**
     * Gets the item that is associated with the specified key.
     * 
     * @param   key     The key of associated with a value.
     * @return  item    The item associated with the key.
     */
    public function getItem($key) {
        if($this->containsKey($key)) {
            return $this->dictionary[$key];	
        }

        return null;
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

    public function getModel() {
        return $this->model;	
    }

    public function setModel($model) {
        $this->model = $model;	
    }

	/**
     * Adds an item to the ICollection.
     * 
     * @param   item    The item that will be added to the collection.
     */
    public function add($item) {
        $this->dictionary[$item->key] = $item->value;
    }

    /**
     * Removes all items from the ICollection.
     */
    public function clear() {
        $this->dictionary = array();
    }

    /**
     * Determines whether the ICollection contains a specific value.
     * 
     * @param   item    The item that should be located in the ICollection.
     */
    public function contains($item) {
        return $this->dictionary[$item->key]==$item->value;
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
     * Copies the elements of the ICollection to an Array, starting at a particular Array index.
     * 
     * @param   array   The one-dimensional Array that is the destination of the elements copied from ICollection. The Array must have zero-based indexing.
     * @param   index   The zero-based index in array at which copying begins.
     */
    public function copyTo(&$array, $index) {
        #TODO
    }

    /**
     * Returns an enumerator that iterates through a collection.
     * 
     * @return  enumerator  The enumerator that can be used to iterate trough a collection.
     */
    public function getEnumerator() {
        #TODO
    }

    /**
     * Removes the first occurrence of a specific object from the ICollection.
     * 
     * @param   item    The object to remove from the ICollection.
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
	

    /**
     * The next 4 methods are used for operatoroverloading. This makes it possible to get, set, etc. values as follows
     * 
     * $dictionary['histranger'] = 'rocks';
     */

    /**
     * Assigns a value to the specified key.
     * 
     * @param   key     The key to assign the value to.
     * @param   value   The value to set.
     */
    public function offsetSet($key, $value) {
        if(is_null($key)) {
            $this->dictionary[] = $value;
        } else {
            $this->dictionary[$key] = $value;
        }
    }
	
	/**
     * Whether or not a key exists. Used when you use isset() or empty().
     * 
     * @param   key     The key that should be looked for.
     */
    public function offsetExists($key) {
        return isset($this->dictionary[$key]);
    }
	
    /**
     * Executed when unset is called on the element.
     * 
     * @param   key     The key of the value that should be unset.
     */
    public function offsetUnset($key) {
        unset($this->dictionary[$key]);
    }
	
	/**
     * Returns the value at specified key.
     * 
     * @param   key     The key of which value should be retrieved.
     */
    public function offsetGet($key) {
        return isset($this->dictionary[$key]) ? $this->dictionary[$key] : null;
    }
}
?>