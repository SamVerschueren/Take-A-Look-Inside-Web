<?php
require_once('system/web/mvc/Controller.php');

class MapController extends Controller {

    public function index($token=null) {
        $this->viewData['title'] = 'Map - Take A Look Inside';
        $this->viewData['menu'] = 'map';
        
        if($token!=null) {
            $this->viewData['redirect'] = $token;
        }
        
        return $this->view();
    }
    
    public function route() {
        // The domains we're allowed to contact
        $allowedDomains = array('http://gazetteer.openstreetmap.org/', 
                            'http://nominatim.openstreetmap.org/', 
                            'http://dev.openstreetmap.nl/', 
                            'http://www.yournavigation.org/',
                            'http://yournavigation.org/');
        
        // The actual form action
        $action = $_GET['url'];
        
        // Submission method
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Query string
        $fields = '';
        
        // Check the url for allowed domains
        $fail = true;
        foreach ($allowedDomains as $domain)
        {
            if (strpos(substr($action, 0, strlen($domain)), $domain) !== false)
            {
                $fail = false;
            break;
            }
        }
        
        if ($fail == true)
        {
            exit("Domain name '".$action."' not allowed. Access denied.");
        }
        
        // Prepare the fields for query string, don't include the action URL OR method
        if (count($_GET) > 2)
        {
            foreach ($_GET as $key => $value)
            {
                if ($key != 'url' && $key != 'method')
                {
                    $fields .= $key . '=' . rawurlencode($value) . '&';
                }
            }
        }
        
        // Strip the last comma
        $fields = substr($fields, 0, strlen($fields) - 1);
        
        // Initiate cURL
        $ch = curl_init();
        
        // Do we need to POST of GET ?
        if (strtoupper($method) == 'POST')
        {   
            curl_setopt($ch, CURLOPT_URL, $action);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
        else
        {
            curl_setopt($ch, CURLOPT_URL, $action . '?' . $fields);   
        }
        
        // Follow redirects and return the transfer
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        curl_setopt($ch, CURLOPT_USERAGENT, "transport.php (CURL)");
         
        // Get result and close cURL
        $result = curl_exec($ch);
        
        
        $curl_info = curl_getinfo($ch);
        
        curl_close($ch);
        
        // Return the response
        header("Content-type: ".$curl_info['content_type']);
        header('Access-Control-Allow-Origin: *');
        //header('Content-Type: application/json');
        echo $result;
        
        exit();
    }
}
?>