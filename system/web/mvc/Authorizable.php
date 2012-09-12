<?php
/**
 * Lett a controller class implement this if you want to add security to the controller.
 * 
 * @package system.web.mvc
 * @since 2012-09-12
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
interface Authorizable {
    /**
     * The implemenation tells the framework if the user is authorized.
     * 
     * @return  boolean     True if the user is authorized, otherwhise false
     */
    public function authorize();
    
    /**
     * The implementation tells the framework what is has to do when authorization fails.
     * 
     * @return  result      An action result has to be returned.
     */
    public function onAuthenticationError();
}
?>