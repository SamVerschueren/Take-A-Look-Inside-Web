<?php
require_once('system/web/mvc/ViewResult.php');

class ViewEngine {
    
    private static $instance;
    private $viewResult;
    
    private function __construct() {
        
    }
    
    public static function getInstance() {
        if(!isset(self::$instance)) {
            self::$instance = new ViewEngine();
        }
        
        return self::$instance;
    }
    
    public function setViewResult(ViewResultBase $viewResult) {
        $this->viewResult = $viewResult;
    }
    
    public function getViewResult() {
        return $this->viewResult;
    }
    
    public function render() {
        try {
            $viewData = $this->getViewResult()->getViewData();
            $model = $viewData['model'];
            
            include($this->viewResult->findView());
        }
        catch(FileNotFoundException $ex) {
            echo '<div id="error">' . $ex->getMessage() . '</div>';
        }
    }
}
?>