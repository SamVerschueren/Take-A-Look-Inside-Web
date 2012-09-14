<?php
require_once('system/web/mvc/Controller.php');

/**
 * This Controller is only used when the user navigates with a computer to the server.
 * 
 * @package controllers
 * @since 2012-09-12
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class DesktopController extends Controller {
    
    /**
     * The index page of the desktop. The menu should be hidden.
     * 
     * @return  viewResult      The view can be found in views/desktop/index.phtml
     */
    public function index() {
        $this->viewData['menu'] = 'hide';
        
        return $this->view();
    }
    
    /**
     * The map page of the desktop. Menu is also hidden.
     * 
     * @return  viewResult      The view can be found in views/desktop/map.phtml
     */
    public function map() {
        $this->viewData['menu'] = 'hide';
        
        return $this->view();
    }
}
?>