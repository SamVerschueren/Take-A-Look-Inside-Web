<?php
require_once('system/web/mvc/Controller.php');

require_once('models/DAL/MovieMapper.php');
require_once('models/DAL/DeviceMapper.php');
require_once('models/DAL/BuildingMapper.php');

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