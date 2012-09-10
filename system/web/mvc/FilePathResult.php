<?php
require_once('FileResult.php');

/**
 * Sends the contents of a file to the response.
 * 
 * @package system.web.mvc
 * @since 2012-07-26
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class FilePathResult extends FileResult {
    
    private $fileName;    
    
    public function __construct($fileName, $contentType) {
        parent::__construct($contentType);
        
        $this->setFileName($fileName);
    }
    
    /**
     * Sets the path of the file that is sent to the response.
     * 
     * @param   fileName        The path of the file that is sent to the response.
     */
    private function setFileName($fileName) {
        $this->fileName = $fileName;
    }
    
    /**
     * Gets the path of the file that is sent to the response.
     * 
     * @return  fileName        The path of the file that is sent to the response.
     */
    public function getFileName() {
        return $this->fileName;
    }
    
    /**
     * Sets the content-disposition header so that a file-download dialog box is displayed in the browser with the specified file name.
     * 
     * @param   fileDownloadName            The name of the file.
     */
    public function setFileDownloadName($fileDownloadName) {
        if($fileDownloadName == null || trim($fileDownloadName) == '') {
            $pos = strrpos($this->getFileName(), '/');
            
            $fileDownloadName = $pos===false?$this->getFileName():substr($this->getFileName(), $pos+1);
        }
       
        parent::setFileDownloadName($fileDownloadName);
    }
    
    /**
     * Enables processing of the result of an action method by a custom type that inherits from the ActionResult class.
     */    
    public function executeResult() {
        header('Content-disposition: attachment; filename=' . $this->getFileDownloadName());
        header('Content-Type: ' . $this->getContentType());
        
        readfile($this->getFileName());
        
        exit();
    }
}
?>