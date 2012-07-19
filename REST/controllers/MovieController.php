<?php
require_once('Controller.php');
require_once('models/ReaderFactory.php');

/**
 * The Controller that handles the Buildings
 *
 * @package TakeALookInside/controllers
 * @author Sam Verschueren  <sam@irail.be>
 */
class MovieController extends Controller {
    
    /**
     * Getting the information of a movie.
     * Gets parameters from URL to determine which information to return.
     * Parameters are passed to a SQLreader, which queries the DB to get the results.
     * Based on the 'format' parameter, the corresponding printer is called.
     * 
     * @param   array   $parameters The parameters of the url.
     * @return  void
     */
    public function get($parameters) {
        //Security:
        //Checks if the device is present in the DB in order to determin if the device is allowed to 
        //watch the video.
        //This prevents users from watching videos without scanning the QR-code/watching them from their computer.
        if(!isset($_GET['device']) || !parent::devicePresentInDb($_GET['device'])){
            echo 'Can\'t access access the file.';
        }
        else{
            //Remove device from get
            //Otherwise the device will be parsed by the SQL readed, but SQL reader doesn't need this information,
            //it's only used for security reasons. Leaving this device set causes problems because it's not filter 
            //but SQL reader expects filters.
            unset($_GET['device']);
            
            
            //Get dataformat
            $dataFormat = isset($_GET['dataFormat'])?$_GET['dataFormat']:'';
            $outputFormat = $parameters['format'];
            
            //Create corresponding reader
            $reader = ReaderFactory::createReader($dataFormat);
            $printer = PrinterFactory::createPrinter($outputFormat);
                        
            //Check if params are valid
            if(!$reader->isValid($parameters)) {
                throw new InvalidArgumentException('URL parameters are not valid');
            }
            
            //Get filters
            $resource = 'movie';
            $restrictions = parent::splitParameters($parameters['parameters']);
             
            //Read data from DB using these filters
            $data = $reader->execute($resource, $restrictions);
            
            //Check printformat
            if($printer instanceof JSONPrinter) {
                $buildingData = $reader->execute('building', array('movieID' => $data['movie'][0]['movieID'], 'select' => 'buildingID;name'));
            
                $file = '../mov/' . $data['movie'][0]['movie'];
                
                $movie = array();
                $movie['size'] = round(filesize($file)/1024, 2);
                $movie['token'] = $data['movie'][0]['qrID'];
                $movie['buildingID'] = $buildingData['building'][0]['buildingID'];
                $movie['buildingName'] = $buildingData['building'][0]['name'];
                //$movie['movie'] = 'http://tali.irail.be/mov/' . $data['movie'][0]['movie'];
                
                
                //Print in JSON
                $printer->doPrint($movie);
            }
            else if($printer instanceof GP3Printer) {
                
                //'Print' in GP3, --> return movie in GP3 format in order to play it.
                $printer->doPrint($data['movie'][0]);  
            }
        }
    }
}
?>