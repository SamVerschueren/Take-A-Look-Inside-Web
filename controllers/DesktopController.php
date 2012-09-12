<?php
require_once('system/web/mvc/Controller.php');

class DesktopController extends Controller {
    
    public function index() {
        $this->viewData['menu'] = 'hide';
        
        return $this->view();
    }
    
    public function map() {
        $this->viewData['menu'] = 'hide';
        
        return $this->view();
    }
}
?>