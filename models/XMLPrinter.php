<?php
include_once('IPrinter.php');

/**
 * Implementation of a printer that prints in the XML format.
 *
 * @package TakeALookInside/models
 * @author Sam Verschueren  <sam@irail.be>
 */
class XMLPrinter implements IPrinter{
    
    /**
     * Print in XML.
     *
     * @param   data    $data   The array object to print in XML.
     */
    public function doPrint(array $data) {
        header('Content-type: text/xml');
        
        $keys = array_keys($data);

        echo '<?xml version="1.0" encoding="UTF-8" ?>';
        echo '<' . strtolower($keys[0]) . 's>';
        echo $this->arrayToXml($data[$keys[0]], strtolower($keys[0]));
        echo '</' . strtolower($keys[0]) . 's>';
    }
    
    /**
     * Recursive helper method to convert the data into xml
     * 
     * @param   $array      array       The array that needs to be converted in XML.
     * @param   $nodeName   nodeName    The nodename
     */
    private function arrayToXml($array, $nodeName) {
        $xml = '';
    
        if (is_array($array) || is_object($array)) {
            foreach ($array as $key=>$value) {
                if (is_numeric($key)) {
                    $key = $nodeName;
                }
    
                $xml .= '<' . $key . '>' . $this->arrayToXml($value, $nodeName) . '</' . $key . '>';
            }
        } else {
            $xml = htmlspecialchars($array, ENT_QUOTES) . "\n";
        }

        return $xml;
    }
}
?>