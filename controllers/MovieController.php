<?php
require_once('system/web/mvc/Controller.php');

require_once('models/DAL/MovieMapper.php');
require_once('models/DAL/DeviceMapper.php');
require_once('models/DAL/BuildingMapper.php');

/**
 * This Controller is used to retrieve data about the movie. It can also play the movie.
 * 
 * @package controllers
 * @since 2012-09-16
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class MovieController extends Controller {
    
    private $movieMapper;
    private $deviceMapper;
    private $buildingMapper;
    
    public function __construct() {
        parent::__construct();
        
        $this->movieMapper = new MovieMapper();
        $this->deviceMapper = new DeviceMapper();
        $this->buildingMapper = new BuildingMapper();
    }
    
    /**
     * Plays the movie with that corresponds to the movieID.
     * 
     * @param   id              The id of the movie.
     * @param   device          The deviceID of the user.
     * @return  streamResult    The movie that will be streamed.
     */
    public function play($id, $device) {
        try {
            $this->deviceMapper->findByDeviceId($device);
            
            $movie = $this->movieMapper->findByQrId($id);
            
            return $this->stream('content/movie/' . $movie->getFile(), 'video/3gpp');
        }
        catch(SQLException $ex) {
            // If an exception occurs, this means that there is no device or no movie with that specified id.
            echo 'Can not play this movie.';
            exit();
        }
    }
    
    /**
     * Outputs information about the movie.
     * 
     * @param   id          The id of the movie.
     * @param   device      The deviceID of the user that wishes information about the movie.
     * @return  json        Information about the movie in jsonformat.
     */
    public function size($id, $device) {
        try {
            $this->deviceMapper->findByDeviceId($device);
            $building = $this->buildingMapper->findByQrToken($id);
            
            $result = array();
            $result['size'] = round(filesize('content/movie/' . $building->getMovie()->getFile())/1024, 2);
            $result['token'] = $building->getMovie()->getQrToken();
            $result['building']['id'] = $building->getId();
            $result['building']['name'] = $building->getName();
            
            $json = $this->json($result);
            $json->setJsonRequestBehaviour(JsonRequestBehaviour::AllowGet);
            
            return $json;
        }
        catch(SQLException $ex) {
            echo 'Can not determine the size of this movie.';
            exit();
        }
    }
}
?>