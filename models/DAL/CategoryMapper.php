<?php
require_once('models/domain/Category.php');

require_once('Mapper.php');

/**
 * This class maps every SQL category to a PHP category
 * 
 * @package models.DAL
 * @since 2012-09-07
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class CategoryMapper extends Mapper {
    
    /**
     * Returns a Collection of all objects for the given mapper.
     * 
     * @return  objects     Array of all the objects.
     * @throws  exception   UnsupportedOperationException if method is not overriden
     */
    public function findAllObjects() {
        $resultset = mysql_query("SELECT id, name FROM category");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the categories.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No categories where found.');
        }
        
        $result = array();
        
        while($data = mysql_fetch_assoc($resultset)) {
            $category = new Category($data['name']);
            $category->setId($data['id']);
            
            $result[] = $category;
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
        $resultset = mysql_query("SELECT id, name FROM category WHERE id='" . mysql_real_escape_string($id) . "'");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the categories.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No categories where found.');
        }
        
        $data = mysql_fetch_assoc($resultset);
        
        $category = new Category($data['name']);
        $category->setId($data['id']);
        
        return $category;
    }
}
?>