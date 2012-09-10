<?php
require_once('System/Collections/IEnumerable.php');

/**
 * Defines methods to manipulate generic collections.
 * 
 * @package system.collections.generic
 * @since 2012-06-29
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
interface ICollection extends IEnumerable {
    /**
     * Gets the number of elements contained in the ICollection.
     * 
     * @return  number  The number of elements contained in the ICollection.
     */
    public function count();
    
    /**
     * Gets a value indicating whether the ICollection is read-only.
     * 
     * @return  boolean true if the ICollection is read-only; otherwise, false.
     */
    public function isReadOnly();

    /**
     * Adds an item to the ICollection.
     * 
     * @param   item    The item that will be added to the collection.
     */
    public function add($item);
    
    /**
     * Removes all items from the ICollection.
     */
    public function clear();
    
    /**
     * Determines whether the ICollection contains a specific value.
     * 
     * @param   item    The item that should be located in the ICollection.
     */
    public function contains($item);
    
    /**
     * Copies the elements of the ICollection to an Array, starting at a particular Array index.
     * 
     * @param   array   The one-dimensional Array that is the destination of the elements copied from ICollection. The Array must have zero-based indexing.
     * @param   index   The zero-based index in array at which copying begins.
     */
    public function copyTo(&$array, $index);
    
    /**
     * Removes the first occurrence of a specific object from the ICollection.
     * 
     * @param   item    The object to remove from the ICollection.
     */
    public function remove($item);
}
?>