<?php
require_once('system/exceptions/runtime/ClassCastException.php');

require_once('models/domain/Movie.php');

require_once('Mapper.php');

/**
 * This class maps every SQL movie to a PHP movie
 * 
 * @package models.DAL
 * @since 2012-09-07
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class MovieMapper extends Mapper {
    /**
     * Create the given object in a persistent data store.
     * 
     * @param   object      The object that should be created in the database.
     * @throws  exception   InvalidArgumentException if parameter is not an object.
     */
    public function create($object) {
        if(!($object instanceof Movie)) {
            throw new ClassCastException('Could not cast the object to Movie');
        }
        
        mysql_query("INSERT INTO movie(movie, dateTime, qrID) VALUES('" . mysql_real_escape_string($object->getFile()) . "', FROM_UNIXTIME('" . $object->getDateTime()->getTimestamp() . "'), '" . mysql_real_escape_string($object->getQrToken()) . "')");
    
        $object->setId(mysql_insert_id());
    }
        
    /**
     * Returns a Collection of all objects for the given mapper.
     * 
     * @return  objects     Array of all the objects.
     * @throws  exception   UnsupportedOperationException if method is not overriden
     */
    public function findAllObjects() {
        $resultset = mysql_query("SELECT id, movie, dateTime, qrID FROM movie");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the movies.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No movies where found.');
        }
        
        $result = array();
        
        while($data = mysql_fetch_assoc($resultset)) {
            $movie = new Movie($data['movie'], $data['qrID'], new DateTime($data['dateTime']));
            $movie->setId($data['id']);
            
            $result[] = $movie;
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
        $resultset = mysql_query("SELECT id, movie, dateTime, qrID FROM movie WHERE id='" . mysql_real_escape_string($id) . "'");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the movie with id ' . $id . '.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No movie found with id ' . $id . '.');
        }
        
        $data = mysql_fetch_assoc($resultset);
        
        $movie = new Movie($data['movie'], $data['qrID'], new DateTime($data['dateTime']));
        $movie->setId($data['id']);
        
        return $movie;
    }
    
    public function findByQrId($id) {
        $resultset = mysql_query("SELECT id, movie, dateTime, qrID FROM movie WHERE qrID='" . mysql_real_escape_string($id) . "'");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the movie with id ' . $id . '.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No movie found with id ' . $id . '.');
        }
        
        $data = mysql_fetch_assoc($resultset);
        
        $movie = new Movie($data['movie'], $data['qrID'], new DateTime($data['dateTime']));
        $movie->setId($data['id']);
        
        return $movie;
    }
}
?>