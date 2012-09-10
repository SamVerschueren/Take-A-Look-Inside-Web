<?php
require_once('ActionResult.php');

/**
 * Represents a base class that is used to send binary file content to the response.
 * 
 * @package system.web.mvc
 * @since 2012-06-23
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
abstract class FileResult extends ActionResult {
	
    private $contentType;
    private $fileDownloadName;
    
    /**
     * Initializes a new instance of the FileResult class.
     * 
     * @param   contentType                 The type of the content.
     * @throws  invalidArgumentException    If the content type is empty.
     */
    public function __construct($contentType) {
        $this->setContentType($contentType);
    }
	
    /**
     * Sets the content type to use for the response.
     * 
     * @param   contentType                 The type of the content.
     */
	private function setContentType($contentType) {
	    if($contentType == null || trim($contentType) == '') {
	        throw new InvalidArgumentException('The content type can not be emtpy.');
	    }
        
		$this->contentType = $contentType;	
	}
    
    /**
     * Gets the content type to use for the response.
     * 
     * @return  contentType                 The type of the content
     */
    public function getContentType() {
        return $this->contentType;	
    }
    
    /**
     * Sets the content-disposition header so that a file-download dialog box is displayed in the browser with the specified file name.
     * 
     * @param   fileDownloadName            The name of the file.
     */
    public function setFileDownloadName($fileDownloadName) {
        $this->fileDownloadName = $fileDownloadName;	
    }
    
    /**
     * Gets the content-disposition header so that a file-download dialog box is displayed in the browser with the specified file name.
     * 
     * @return  fileDownloadName            The name of the file.
     */
    public function getFileDownloadName() {
        return $this->fileDownloadName;	
    }
}
?>