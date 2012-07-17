<?php
require_once('Controller.php');
require_once('models/ReaderFactory.php');

/**
 * Buildingcontroller
 *
 * @package TakeALookInside/controllers
 * @author Sam Verschueren  <sam@irail.be>
 */
class SeenController extends Controller {
    
    /**
     * Posting things
     * 
     * @param   parameters  $parameters The parameters of the url.
     */
    public function post($parameters) {
        echo "Test";
    }
}
?>