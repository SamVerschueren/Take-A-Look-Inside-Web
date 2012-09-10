<?php
require_once('ActionResult.php');
require_once('IView.php');
require_once('ViewEngine.php');

/**
 * Represents a base class that is used to provide the model to the view and then render the view to the response.
 * 
 * @package system.web.mvc
 * @since 2012-07-27
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
abstract class ViewResultBase extends ActionResult {
    
    private $view;
    private $viewName;
    private $viewData;
    private $controller;
    
    public function __construct() {
        
    }
    
    public function setController(Controller $controller) {
        $this->controller = $controller;
    }
    
    public function getController() {
        return $this->controller;
    }
    
    /**
     * Gets the IView object that is rendered to the response.
     * 
     * @return  view        The view.
     */
    public function getView() {
        return $this->view;
    }
    
    /**
     * Sets the IView object that is rendered to the response.
     * 
     * @param   view        The view.
     */
    public function setView(IView $view) {
        $this->view = $view;
    }
    
    /**
     * Gets the name of the view to render.
     * 
     * @return  viewName    The name of the view.
     */
    public function getViewName() {
        return !isset($this->viewName)?'':$this->viewName;
    }
    
    /**
     * Sets the name of the view to render.
     * 
     * @param   viewName    The name of the view.
     */
    public function setViewName($viewName) {
        $this->viewName = $viewName;
    }
    
    /**
     * Sets the view data ViewDataDictionary object for this result.
     * 
     * @return  viewData    The view data.
     */
    public function setViewData(ViewDataDictionary $viewData) {
        $this->viewData = $viewData;
    }
    
    /**
     * Gets the view data ViewDataDictionary object for this result.
     * 
     * @return  viewData    The view data.
     */
    public function getViewData() {
        return $this->viewData;
    }
}
?>