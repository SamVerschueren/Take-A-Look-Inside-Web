<?php
require_once('ActionResult.php');

/**
 * Controls the processing of application actions by redirecting to a specified URI.
 * 
 * @package system.web.mvc
 * @since 2012-06-23
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class RedirectResult extends ActionResult {
    
    private $url;
    private $permanent;
    
    public function __construct($url, $permanent=false) {
        $this->setUrl($url);
        $this->setPermanent($permanent);
    }
    
    /**
     * Sets the target URL.
     * 
     * @param   url         The target url.
     */
    private function setUrl($url) {
        $this->url = $url;
    }
    
    /**
     * Gets the target URL.
     * 
     * @param   url         The target url.
     */
    public function getUrl() {
        return $this->url;
    }
    
    /**
     * Set whether the redirect is permanent or not.
     * 
     * @param   permanent   true if redirect is permanent, false otherwise.
     */
    private function setPermanent($permanent) {
        $this->permanent = $permanent;	
    }
    
    /**
     * Gets indication whether the redirect is permanent or not.
     * 
     * @return  permanent   true if redirect is permanent, false otherwise.
     */
    public function isPermanent() {
        return $this->permanent;
    }

    /**
     * Enables processing of the result of an action method by a custom type that inherits from the ActionResult class.
     */
    public function executeResult() {
        if($this->isPermanent()) {
            header('HTTP/1.1 301 Moved Permanently');
        }
        
        header('Location: ' . $this->getUrl());
    }
}
?>