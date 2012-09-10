<?php
/**
 * Exposes the enumerator, which supports a simple iteration over a non-generic collection.
 * 
 * @package system.collections
 * @since 2012-06-29
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
interface IEnumerable {
    /**
     * Returns an enumerator that iterates through a collection.
     * 
     * @return  enumerator  The enumerator that can be used to iterate trough a collection.
     */
    public function getEnumerator();	
}
?>