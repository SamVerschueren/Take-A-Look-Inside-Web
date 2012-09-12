<?php
require_once('system/web/mvc/Controller.php');
require_once('system/web/mvc/Authorizable.php');

require_once('content/libs/MobileDetect.php');

class DownloadController extends Controller implements Authorizable {

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