<?php
require_once('system/web/mvc/Controller.php');
require_once('system/web/routing/RouteValueDictionary.php');

require_once('content/libs/MobileDetect.php');

/**
 * Controller that handles all the incomming requests of the homepage. The Controller implements Authorizable. This means that it can only
 * be accessed if the user is authorized for it. In this case, the user has to be on a mobile device to watch this.
 * 
 * @package controllers
 * @since 2012-09-08
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class HomeController extends Controller implements Authorizable {

    /**
     * Handles the call to Home/Index
     * 
     * @param   token       If the token is not null, redirect to the map.
     * @return  view        The view can be found in views/home/index.phtml
     */
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
    
    /**
     * Handles the call to Home/Credits
     * 
     * @return  view        The view can be found in views/home/credits.phtml
     */
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