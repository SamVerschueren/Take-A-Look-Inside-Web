<?php
require_once('system/web/mvc/Controller.php');
require_once('system/web/mvc/Authorizable.php');

require_once('content/libs/MobileDetect.php');

/**
 * This Controller handles all the calls to /Download. The Controller implements Authorizable. This means that it can only
 * be accessed if the user is authorized for it. In this case, the user has to be on a mobile device to watch this.
 * 
 * @package controllers
 * @since 2012-09-08
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class DownloadController extends Controller implements Authorizable {

    /**
     * Handles the call to Download/Index
     * 
     * @return  viewResult  The view can be found in views/download/index.phtml
     */
    public function index() {
        $this->viewData['title'] = 'Download - Take A Look Inside';
        $this->viewData['menu'] = 'download';
        
        return $this->view();
    }
    
    /**
     * The implemenation tells the framework if the user is authorized.
     * 
     * @return  boolean     True if the user is authorized, otherwhise false
     */
    public function authorize() {        
        $mobileDetect = new MobileDetect();
        
        return $mobileDetect->isMobile();
    }
    
    /**
     * The implementation tells the framework what is has to do when authorization fails.
     * 
     * @return  result      An action result has to be returned.
     */
    public function onAuthenticationError() {
        $routeValueDictionary = new RouteValueDictionary();
        $routeValueDictionary->add('controller', 'Desktop');
        
        return $this->redirectToAction('Index', $routeValueDictionary);
    }
}
?>