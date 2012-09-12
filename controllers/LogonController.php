<?php
require_once('system/web/mvc/Controller.php');

require_once('models/DAL/UserMapper.php');

class LogonController extends Controller {
    
    private $userMapper;
    
    public function __construct() {
        parent::__construct();
        
        $this->userMapper = new UserMapper();
    }
    
    public function index() {
        return $this->view();
    }
    
    public function logon($username, $password, $redirectUrl) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $user = $this->userMapper->findByNameAndPassword($username, $password);
                
                $_SESSION[Config::$SESSION_NAME] = serialize($user);
                
                if(isset($redirectUrl)) {
                    return $this->redirectUrl;
                }
                else {
                    $routeValueDictionary = new RouteValueDictionary();
                    $routeValueDictionary->add('controller', 'Admin');
                    
                    return $this->redirectToAction('Index', $routeValueDictionary);
                }
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