<?php
require_once('system/web/mvc/Controller.php');

require_once('models/DAL/UserMapper.php');

/**
 * This controller is used to logon to the adminpanel.
 * 
 * @package controllers
 * @since 2012-09-11
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class LogonController extends Controller {
    
    private $userMapper;
    
    public function __construct() {
        parent::__construct();
        
        $this->userMapper = new UserMapper();
    }
    
    /**
     * Handles the call to Logon/Index. It just renders a page where you can logon.
     * 
     * @return  viewResult      The view can be found in views/logon/index.phtml
     */
    public function index() {
        return $this->view();
    }
    
    /**
     * Try's to logon to the system. Redirects back to the index when it fails to do so.
     * 
     * @param   username        The username that the user entered.
     * @param   password        The password that the user entered.
     * @return  redirectResult  It redirects to the Admin panel or back the the index page with an error message.
     */
    public function logon($username, $password) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $user = $this->userMapper->findByNameAndPassword($username, $password);
                
                $_SESSION[Config::$SESSION_NAME] = serialize($user);
                
                $routeValueDictionary = new RouteValueDictionary();
                $routeValueDictionary->add('controller', 'Admin');
                
                return $this->redirectToAction('Index', $routeValueDictionary);
            }
            catch(SQLException $ex) {
                $this->viewData['error'] = $ex->getMessage();
                
                return $this->redirectToAction('Index');
            }
        }
        else {
            return $this->redirectToAction('Index');
        }
    }
    
    /**
     * This action destroys the session an redirects back to the homepage.
     * 
     * @return  redirectResult  It redirects back to the homepage.
     */
    public function logout() {
        $_SESSION[Config::$SESSION_NAME] = '';
        
        unset($_SESSION[Config::$SESSION_NAME]);
        
        session_destroy();
        
        $routeValueDictionary = new RouteValueDictionary();
        $routeValueDictionary->add('controller', 'Home');
        
        return $this->redirectToAction('Index', $routeValueDictionary);
    }
}
?>