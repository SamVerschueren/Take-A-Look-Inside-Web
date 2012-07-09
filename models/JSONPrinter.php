<?php

include_once('IPrinter.php');
/**
 * Implementation of a printer that prints in the JSON format.
 *
 * @package TakeALookInside/models
 * @author Lieven Benoot  <lieven.benoot@irail.be>
 */
class JSONPrinter implements IPrinter{
    
    /**
     * Print in JSON.
     *
     * @param   toPrint     $toPrint    The querry object to print in JSON.
     */
    public function doPrint($toPrint){
        
        //dummycode to simulate $toPrint content.
        //$link = mysql_connect('localhost','root','root') or die('Cannot connect to the DB');
        //mysql_select_db('TakeALookInside',$link) or die('Cannot select the DB');        
        //$result = mysql_query("select * from Category where categoryID=1");
        
        $toPrint=$result;
        $rows = array();
        while($r = mysql_fetch_assoc($toPrint)) {
            $rows['Category'][] = $r;
           //foreach ($r as $field)
           //     echo $field . "\n";
        }
        print json_encode($rows);

         
    }
    

}
 

?>
