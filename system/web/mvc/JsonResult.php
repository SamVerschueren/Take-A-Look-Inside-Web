<?php
require_once('ActionResult.php');
require_once('JsonRequestBehaviour.php');

/**
 * Represents a class that is used to send JSON-formatted content to the response.
 * 
 * @package system.web.mvc
 * @since 2012-06-23
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class JsonResult extends ActionResult {

    private $contentEncoding;
    private $contentType;
    private $data;
    private $jsonRequestBehaviour;

    public function __construct() {
        $this->setContentEncoding("gzip");
        $this->setContentType("application/json");
        $this->setJsonRequestBehaviour(JsonRequestBehaviour::DenyGet);
    }

	/**
     * Sets the content encoding.
     * 
     * @param   contentEncoding     The content encoding.
     */
    public function setContentEncoding($contentEncoding) {
        $this->contentEncoding = $contentEncoding;	
    }

    /**
     * Gets the content encoding.
     * 
     * @return  contentEncoding     The content encoding.
     */
    public function getContentEncoding() {
        return $this->contentEncoding;	
    }

    /**
     * Sets the type of the content.
     * 
     * @param   contentType         The type of content.
     */
    public function setContentType($contentType) {
        $this->contentType = $contentType;	
    }

    /**
     * Gets the type of the content.
     * 
     * @return  contentType         The type of content.
     */
    public function getContentType() {
        return $this->contentType;	
    }

    /**
     * Sets the data.
     * 
     * @param   data                The data.
     */
    public function setData($data) {
        $this->data = $data;	
    }

    /**
     * Gets the data.
     * 
     * @return  data                The data.
     */
    public function getData() {
        return $this->data;	
    }

    /**
     * Sets a value that indicates whether HTTP GET requests from the client are allowed.
     * 
     * @param   behaviour           A value that indicates whether HTTP GET requests from the client are allowed.
     */
    public function setJsonRequestBehaviour($jsonRequestBehaviour) {
        $this->jsonRequestBehaviour = $jsonRequestBehaviour;	
    }
    
    /**
     * Sets a value that indicates whether HTTP GET requests from the client are allowed.
     * 
     * @return   behaviour           A value that indicates whether HTTP GET requests from the client are allowed.
     */
    public function getJsonRequestBehaviour() {
        return $this->jsonRequestBehaviour;	
    }

    /**
     * Enables processing of the result of an action method by a custom type that inherits from the ActionResult class.
     */
    public function executeResult() {
        header('Accept-Encoding: ' . $this->getContentEncoding());
        header('Content-type: ' . $this->getContentType());
        
        if($this->getJsonRequestBehaviour() == JsonRequestBehaviour::DenyGet && $_SERVER['REQUEST_METHOD'] == 'GET') {
        	throw new BadFunctionCallException('JSON Get is denied.');
        }
        
        echo json_encode($this->getData());
        
        exit();
    }
}
?>