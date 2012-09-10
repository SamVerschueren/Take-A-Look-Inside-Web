<?php
require_once('system/web/mvc/Controller.php');

require_once('models/DAL/MovieMapper.php');
require_once('models/DAL/DeviceMapper.php');

class MovieController extends Controller {
    
    private $movieMapper;
    private $deviceMapper;
    
    public function __construct() {
        $this->movieMapper = new MovieMapper();
        $this->deviceMapper = new DeviceMapper();
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
            $movie = $this->movieMapper->findByQrId($id);
            
            $result['size'] = round(filesize('content/movie/' . $movie->getFile())/1024, 2);
            
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