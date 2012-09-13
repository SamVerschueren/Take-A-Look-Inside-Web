<?php
require_once('system/web/mvc/Controller.php');

require_once('models/domain/Device.php');
require_once('models/DAL/DeviceMapper.php');

/**
 * This Controller handles the actions if you want data about the devices.
 * 
 * @package controllers
 * @since 2012-09-11
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class DeviceController extends Controller {
    
    private $deviceMapper;
    
    public function __construct() {
        parent::__construct();
        
        $this->deviceMapper = new DeviceMapper();
    }
    
    /**
     * Returns data about a device. You can add a new device by posting to the server or get information by a get request.
     * 
     * @param   id          The deviceID that you want to know if it exists.
     * @return  json        Json about the device.
     */
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