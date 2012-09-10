<?php
require_once('ICollection.php');

/**
 * Represents a collection of objects that can be individually accessed by index. 
 *
 * @package system.collections.generic
 * @since 2012-06-29
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
interface IList extends ICollection {
    /**
     * Gets the element at the specified index. 
     *
     * @param   index   The index of the item that should be returned.
     * @return  item    The item associated with the index.
     */
    public function getItem($index);
    
    /**
     * Sets the element at the specified index.
     * 
     * @param   index   The place in the collection at which the item will be set.
     * @param   item    The item that will be set at the specified index.
     */
    public function setItem($index, $item);
    
    /**
     * Determines the index of a specific item in the IList.
     * 
     * @param   item    The item in the IList.
     * @return  index   The index of the specified item.
     */
    public function indexOf($item);
    
    /**
     * Inserts an item to the IList at the specified index.
     * 
     * @param   index   The place in the collection at which the item will be inserted.
     * @param   item    The item that will be inserted at the specified index.
     */
    public function insert($index, $item);
    
    /**
     * Removes the item at the specified index.
     * 
     * @param   index   The index at which the item should be removed.
     */
    public function removeAt($index);
}
?>
