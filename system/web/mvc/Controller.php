<?php
require_once('ViewDataDictionary.php');
require_once('JsonResult.php');
require_once('RedirectResult.php');
require_once('FilePathResult.php');
require_once('ViewResult.php');
require_once('PartialViewResult.php');
require_once('RedirectToRouteResult.php');
require_once('system/web/routing/RouteValueDictionary.php');

/**
 * The base class of every controller in the web project
 * 
 * @package system.web.mvc
 * @since 2012-07-23
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class Controller {

    private $actionInvoker;
    protected $viewData;

    public function __construct() {
        $this->viewData = new ViewDataDictionary();
    }
    
    public function getViewData() {
        return $this->viewData;
    }
    
    /**
     * Called when a request matches this controller, but no method with the specified action name is found in the controller.
     * 
     * @param   actionName          The name of the attempted action.
     */
    public function handleUnknownAction($actionName) {
        #TODO
    }

    /**
     * @param   actionInvoker       The IActionInvoker that invoked the action on the controller.
     */
    public function setActionInvoker(IActionInvoker $actionInvoker) {
        $this->actionInvoker = $actionInvoker;
    }
    
    /**
     * @return  actionInvoker       The IActionInvoker that invoked the action on the controller.
     */
    public function getActionInvoker() {
        return $this->actionInvoker;
    }
    
    /**
     * Creates a JsonResult object that serializes the specified object to JavaScript Object Notation (JSON).
     * 
     * @param   data                The JavaScript object graph to serialize.
     */ 
    public function json($data) {
        $jsonResult = new JsonResult();
        $jsonResult->setData($data);
        
        return $jsonResult;
    }

    /**
     * Creates a RedirectResult object that redirects to the specified URL.
     * 
     * @param   url                 The URL to redirect to.
     */
    public function redirect($url) {
        return new RedirectResult($url);
    }
    
    /**
     * Redirects to the specified action using the action name and route values.
     * 
     * @param   actionName          The name of the action.
     * @param   routeValues         The parameters for a route. 
     */
    public function redirectToAction($actionName, RouteValueDictionary $routeValues = null) {
        if($this->viewData instanceof ViewDataDictionary) {
            if(!isset($_SESSION['viewData'])) {
                $_SESSION['viewData'] = serialize($this->viewData);
            }
        }
        
        if($routeValues == null) {
            $routeValues = new RouteValueDictionary();
        }
        
        $routeValues->add('action', $actionName);
        
        return new RedirectToRouteResult($routeValues);
    }
    
    /**
     * Creates a FilePathResult object by using the file name, the content type, and the file download name. The file download name is optional.
     * 
     * @param   fileName            The path of the file to send to the response.
     * @param   contentType         The content type (MIME type).
     * @param   fileDownloadName    The file name to use in the file-download dialog box that is displayed in the browser. (optional)
     */
    public function file($fileName, $contentType, $fileDownloadName=null) {
        $fileResult = new FilePathResult($fileName, $contentType);
        $fileResult->setFileDownloadName($fileDownloadName);
        
        return $fileResult;
    }
    
    /**
     * Creates a ViewResult object by using the model that renders a view to the response.
     * 
     * @param   model               The model that is rendered by the view
     */
    public function view($model = null) {
        if(isset($_SESSION['viewData'])) {
            $viewData = unserialize($_SESSION['viewData']);    
            
            foreach($viewData->getKeys() as $key) {
                $this->viewData[$key] = $viewData[$key];
            }
            
            unset($_SESSION['viewData']);
        }
        
        if(!isset($this->viewData['title'])) {
            $this->viewData['title'] = $this->actionInvoker->getInvokedAction();
        }
        
        if($model != null) {
            $this->viewData['model'] = $model;   
        }
        
        $viewResult = new ViewResult();
        $viewResult->setViewData($this->viewData);
        $viewResult->setController($this->actionInvoker->getInvokedController());
        $viewResult->setViewName($this->actionInvoker->getInvokedAction());
        
        return $viewResult;
    }   
    
    /**
     * Creates a PartialViewResult object that renders a partial view, by using the specified model.
     * 
     * @param   model               The model that is rendered by the view
     */
    public function partialView($model = null) {
        if($model != null) {
            $this->viewData['model'] = $model;
        }
        
        $partialViewResult = new PartialViewResult();
        $partialViewResult->setViewData($this->viewData);
        $partialViewResult->setController($this->actionInvoker->getInvokedController());
        $partialViewResult->setViewName($this->actionInvoker->getInvokedAction());
        
        return $partialViewResult;
    }
}
?>