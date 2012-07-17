<?php
        $reqfile = '../mov/Stadhuis_Piloot.3gp';//'../../../mov/' . $data['movie'];
        $contenttype="video/3gpp";
        
        if(file_exists($reqfile)) {
             
            if($fn=fopen($reqfile, "rba")){
              header("Content-Type: ".$contenttype); 
              header("Content-Length: ".filesize($reqfile)); 
              header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
              header("Pragma: no-cache");
              header("Expires: Mon, 26 Jul 1997 06:00:00 GMT");
              header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0");
              fpassthru($fn);
              fclose($fn);
            }else{
              exit("error....");
            }
            exit();
        }
        else {
            echo 'File does not exists';
        }
?>