<?php
require_once('system/web/mvc/Controller.php');

require_once('models/domain/Device.php');
require_once('models/DAL/DeviceMapper.php');

class DeviceController extends Controller {
    
    private $deviceMapper;
    
    public function __construct() {
        parent::__construct();
        
        $this->deviceMapper = new DeviceMapper();
    }
    
    public function index($id=null) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $device = new Device($_POST['device']);
            
            $this->deviceMapper->create($device);
        }
        else {
            if($id != null) {
                try {
                    $this->deviceMapper->findByDeviceId($id);
                    
                    $result["exists"] = true;
                }
                catch(SQLException $ex) {
                    $result["exists"] = false;
                }
                
                $json = $this->json($result);
                $json->setJsonRequestBehaviour(JsonRequestBehaviour::AllowGet);
                
                return $json;
            }
        }
    }
}
?>