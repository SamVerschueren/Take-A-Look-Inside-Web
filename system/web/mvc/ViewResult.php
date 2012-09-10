<?php
require_once('ViewResultBase.php');

/**
 * Represents a class that is used to render a view by using an IView instance that is returned by an IViewEngine object.
 * 
 * @package system.web.mvc
 * @since 2012-07-27
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class ViewResult extends ViewResultBase {

    private $masterName;
    
    /**
     * Sets the name of the master view (such as a master page or template) to use when the view is rendered.
     * 
     * @param   masterName      The name of the master view.
     */    
    public function setMasterName($masterName) {
        $this->masterName = $masterName;
    }
    
    /**
     * Gets the name of the master view (such as a master page or template) to use when the view is rendered.
     * 
     * @return  masterName      The name of the master view.
     */
    public function getMasterName() {
        return $this->masterName;
    }

    /**
     * Searches different locations to find the file that has to be rendered.
     * 
     * @return  filePath        The file that has to be rendered.
     */
    public function findView() {
        $controllerName = strtolower(get_class($this->getController()));
        
        $folderName = str_replace('controller', '', $controllerName);
        
        $file = $this->getViewName() . '.phtml';
        
        if(file_exists('views/' . $folderName . '/' . $file)) {
            return 'views/' . $folderName . '/' . $file;
        }
        else if(file_exists('views/shared/' . $file)) {
            return 'views/shared/' . $file;
        }
        else {
            throw new FileNotFoundException('The file ' . $file . ' could not be found.');
        }
    }
    
    /**
     * When called by the action invoker, renders the view to the response.
     */
    public function executeResult() {
        ViewEngine::getInstance()->setViewResult($this);
    }
}
?>