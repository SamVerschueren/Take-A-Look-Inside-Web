<?php
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
     * Returns a Collection of all objects for the given mapper.
     * 
     * @return  objects     Array of all the objects.
     * @throws  exception   UnsupportedOperationException if method is not overriden
     */
    public function findAllObjects() {
        $resultset = mysql_query("SELECT movieID, movie, dateTime, qrID FROM movie");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the movies.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No movies where found.');
        }
        
        $result = array();
        
        while($data = mysql_fetch_assoc($resultset)) {
            $movie = new Movie($data['movie'], $data['qrID'], new DateTime($data['dateTime']));
            $movie->setId($data['movieID']);
            
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
        $resultset = mysql_query("SELECT movieID, movie, dateTime, qrID FROM movie WHERE movieID='" . mysql_real_escape_string($id) . "'");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the movie with id ' . $id . '.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No movie found with id ' . $id . '.');
        }
        
        $data = mysql_fetch_assoc($resultset);
        
        $movie = new Movie($data['movie'], $data['qrID'], new DateTime($data['dateTime']));
        $movie->setId($data['movieID']);
        
        return $movie;
    }
    
    public function findByQrId($id) {
        $resultset = mysql_query("SELECT movieID, movie, dateTime, qrID FROM movie WHERE qrID='" . mysql_real_escape_string($id) . "'");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the movie with id ' . $id . '.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No movie found with id ' . $id . '.');
        }
        
        $data = mysql_fetch_assoc($resultset);
        
        $movie = new Movie($data['movie'], $data['qrID'], new DateTime($data['dateTime']));
        $movie->setId($data['movieID']);
        
        return $movie;
    }
}
?>