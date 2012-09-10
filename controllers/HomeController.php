<?php
require_once('system/web/mvc/Controller.php');
require_once('system/web/routing/RouteValueDictionary.php');

/**
 * Controller that handles all the incomming requests of the homepage.
 * 
 * @author Sam Verschueren <sam@iRail.be>
 */
class HomeController extends Controller {

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
}
?>