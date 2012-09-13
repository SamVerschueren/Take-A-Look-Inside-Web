<?php
require_once('system/web/mvc/Controller.php');
require_once('system/web/routing/RouteValueDictionary.php');

require_once('content/libs/MobileDetect.php');

/**
 * Controller that handles all the incomming requests of the homepage.
 * 
 * @author Sam Verschueren <sam@iRail.be>
 */
class HomeController extends Controller implements Authorizable {

    public function index($token) {
        $this->viewData['title'] = 'Home - Take A Look Inside';
        $this->viewData['menu'] = 'home';
        
        // For redirection purposes when the user scanned a QR code and is redirected to the website.
        if($token != null) {
            $routeValueDictionary = new RouteValueDictionary();
            $routeValueDictionary->add('controller', 'Map');
            $routeValueDictionary->add('token', $token);
        
            return $this->RedirectToAction('Index', $routeValueDictionary);
        }
        
        return $this->view();
    }
    
    public function credits(){
        $this->viewData['title'] = 'Credits - Take A Look Inside';        
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
     * The implementation tells the framework what it has to do when authorization fails.
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