<?php
# NOT WORKING
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
     * @param   toPrint     $toPrint    The query object to print in XML.
     */
    public function doPrint(array $toPrint) {
        header('Content-type: text/xml');
        
        echo '<?xml version="1.0" ?>';
        echo '<error>';
        echo '  <message>Not supported yet</message>';
        echo '</error>';

    }
}
?>