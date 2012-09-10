<?php
require_once('ViewResultBase.php');

class PartialViewResult extends ViewResultBase {
    
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
        ViewEngine::getInstance()->render();
        
        exit();
    }
}
?>