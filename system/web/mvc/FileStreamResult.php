<?php
require_once('FileResult.php');
require_once('system/exceptions/io/IOException.php');
require_once('system/exceptions/io/FileNotFoundException.php');

/**
 * Sends the contents of a file to the response.
 * 
 * @package system.web.mvc
 * @since 2012-09-10
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class FileStreamResult extends FileResult {
    
    private $filePath;
    
    public function __construct($filePath, $contentType) {
        parent::__construct($contentType);
        
        $this->setFilePath($filePath);
    }
    
    /**
     * Sets the path of the file that is sent to the response.
     * 
     * @param   filePath        The path of the file that is sent to the response.
     */
    private function setFilePath($filePath) {
        $this->filePath = $filePath;
    }
    
    /**
     * Gets the path of the file that is sent to the response.
     * 
     * @return  filePath        The path of the file that is sent to the response.
     */
    public function getFilePath() {
        return $this->filePath;
    }
    
    /**
     * Enables processing of the result of an action method by a custom type that inherits from the ActionResult class.
     */
    public function executeResult() {
        // check if file can be found
        if(file_exists($this->filePath)) {
            // open file
            if($fn=fopen($this->filePath, "rba")){
                //add headers
                header("Content-Type: "  . $this->getContentType()); 
                header("Content-Length: ".filesize($this->filePath)); 
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                header("Pragma: no-cache");
                header("Expires: Mon, 26 Jul 1997 06:00:00 GMT");
                header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0");
                
                fpassthru($fn);
                fclose($fn);
            } 
            else {
                // file could not be opened.
                throw new IOException('The file ' . $this->filePath . ' could not be opened.');
            }            
        }
        else {
            // file could not be found.
            throw new FileNotFoundException('The file ' . $this->filePath . ' could not be found.');
        }
    }
}
?>