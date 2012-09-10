<?php
require_once('system/web/mvc/Controller.php');

class MapController extends Controller {

    public function index($token=null) {
        $this->viewData['title'] = 'Map - Take A Look Inside';
        $this->viewData['menu'] = 'map';
        
        if($token!=null) {
            $this->viewData['redirect'] = $token;
        }
        
        return $this->view();
    }
    
    public function route($myLat, $myLon, $destLat, $destLon) {
        
        
        exit();
    }
}
?>