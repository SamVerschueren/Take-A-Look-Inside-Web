<?php
require_once('Controller.php');

/**
 * The controller that handles all the request related to the homepage
 * 
 * @author Sam Verschueren  <sam@irail.be>
 */
class HomeController extends Controller {
    
    /**
     * The action index
     */
    public function index() {
        return parent::view();
    }
}
?>