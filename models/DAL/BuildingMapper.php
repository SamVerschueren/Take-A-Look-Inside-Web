<?php
require_once('models/domain/Building.php');
require_once('models/domain/Location.php');

require_once('CategoryMapper.php');
require_once('MovieMapper.php');

require_once('Mapper.php');

/**
 * This class maps every SQL building to a PHP building
 * 
 * @package models.DAL
 * @since 2012-09-07
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class BuildingMapper extends Mapper {
    
    private $categoryMapper;
    private $movieMapper;
    
    public function __construct() {
        $this->categoryMapper = new CategoryMapper();
        $this->movieMapper = new MovieMapper();
    }
    
    /**
     * Returns a Collection of all objects for the given mapper.
     * 
     * @return  objects     Array of all the objects.
     * @throws  exception   UnsupportedOperationException if method is not overriden
     */
    public function findAllObjects() {
        $resultset = mysql_query("SELECT b.id, name, description, infoLink, COUNT(m.buildingID) AS mustSee, longitude, latitude, adres, movieID, categoryID FROM building b LEFT JOIN must_sees m ON b.id=m.buildingID GROUP BY b.id, name, description, infoLink, longitude, latitude, adres, movieID, categoryID ORDER BY mustSee DESC");
     
        if(!$resultset) {
            throw new SQLException('Error while retrieving the buildings.'. mysql_error() );
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No buildings where found.');
        }
        
        $result = array();
        
        while($data = mysql_fetch_assoc($resultset)) {            
            $result[] = $this->createBuilding($data);;
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
        $resultset = mysql_query("SELECT b.id, name, description, infoLink, COUNT(m.buildingID) AS mustSee, longitude, latitude, adres, movieID, categoryID FROM building b LEFT JOIN must_sees m ON b.id=m.buildingID WHERE b.id='" . mysql_real_escape_string($id) . "' GROUP BY b.id, name, description, infoLink, longitude, latitude, adres, movieID, categoryID");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the building with id ' . $id . '.' . mysql_error() );
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No building found with id ' . $id . '.' . mysql_error());
        }
        
        $data = mysql_fetch_assoc($resultset);
        
        return $this->createBuilding($data);;
    }

    public function findByCategoryId($id) {
        $resultset = mysql_query("SELECT b.id, name, description, infoLink, COUNT(m.deviceID) AS mustSee, longitude, latitude, adres, movieID, categoryID FROM building b LEFT JOIN must_sees m ON b.id=m.buildingID WHERE b.categoryID='" . mysql_real_escape_string($id) . "' GROUP BY b.id, name, description, infoLink, longitude, latitude, adres, movieID, categoryID");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the movie with categoryid ' . $id . '.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No building found with categoryid ' . $id . '.');
        }
        
        $result = array();
        
        while($data = mysql_fetch_assoc($resultset)) {
            $result[] = $this->createBuilding($data);
        }
        
        return $result;
    }

    public function findByQrToken($token) {
        $resultset = mysql_query("SELECT b.id, name, description, infoLink, COUNT(m.deviceID) AS mustSee, longitude, latitude, adres, b.movieID, categoryID FROM building b LEFT JOIN must_sees m ON b.id=m.buildingID JOIN movie mv ON b.movieID=mv.id WHERE mv.qrID='" . mysql_real_escape_string($token) .  "' GROUP BY b.id, name, description, infoLink, longitude, latitude, adres, movieID, categoryID");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the movie with qrtoken ' . $token . '.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No building found with qrtoken ' . $token . '.');
        }
        
        $data = mysql_fetch_assoc($resultset);
        
        return $this->createBuilding($data);
    }

    private function createBuilding(array $data) {
        $location = new Location($data['longitude'], $data['latitude'], $data['adres']);
        $movie = $data['movieID']!=null?$this->movieMapper->findByUniqueId($data['movieID']):null;
        $category = $this->categoryMapper->findByUniqueId($data['categoryID']);
        
        $building = new Building($data['name'], $data['description'], $data['infoLink'], $data['mustSee'], $location, $category, $movie);
        $building->setId($data['id']);
        
        return $building;
    }
}
?>