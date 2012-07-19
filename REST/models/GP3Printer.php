<?php
include_once('IPrinter.php');

/**
 * Implementation of a printer that prints in the 3GP video format.
 *
 * @package TakeALookInside/models
 * @author Sam Verschueren  <sam@irail.be>
 */
class GP3Printer implements IPrinter{
    
    /**
     * Print in 3GP.
     *
     * @param   data    $data   The array object to print in 3GP.
     */
    public function doPrint(array $data) {
        $reqfile = '../mov/' . $data['movie'];
        //check if file can be found
        if(file_exists($reqfile)) {
            //open file
            if($fn=fopen($reqfile, "rba")){
                //add headers
                header("Content-Type: video/3gpp"); 
                header("Content-Length: ".filesize($reqfile)); 
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                header("Pragma: no-cache");
                header("Expires: Mon, 26 Jul 1997 06:00:00 GMT");
                header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0");
                
                fpassthru($fn);
                fclose($fn);
            } 
            else{//failed to open              
              exit("error....");
            }            
            exit();
        }
        else {//file can't be found'
            echo 'File does not exists';
        }
    }
}
?>
