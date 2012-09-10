<?php
require_once('system/web/mvc/Controller.php');

class DownloadController extends Controller {

    public function index() {
        $this->viewData['title'] = 'Download - Take A Look Inside';
        $this->viewData['menu'] = 'download';
        
        return $this->view();
    }
}
?>