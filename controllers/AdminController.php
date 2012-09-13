<?php
require_once('system/web/mvc/Controller.php');
require_once('system/web/mvc/Authorizable.php');

require_once('models/DAL/BuildingMapper.php');
require_once('models/DAL/CategoryMapper.php');

require_once('models/domain/User.php');

require_once('viewmodels/BuildingViewModel.php');
require_once('viewmodels/CategoryViewModel.php');

class AdminController extends Controller implements Authorizable {
    
    private $buildingMapper;
    private $categoryMapper;
    private $movieMapper;

    public function __construct() {
        parent::__construct();
        
        $this->buildingMapper = new BuildingMapper();
        $this->categoryMapper = new CategoryMapper();
        $this->movieMapper = new MovieMapper();
    }
    
    public function index() {
        $buildings = $this->buildingMapper->findAllObjects();
        
        $buildingViewModels = array();
        
        foreach($buildings as $building) {
            $buildingViewModels[] = new BuildingViewModel($building);
        }
        
        $user = unserialize($_SESSION[Config::$SESSION_NAME]);
        
        $this->viewData['user'] = $user->getName();
        $this->viewData['menu'] = 'hide';
        
        return $this->view($buildingViewModels);
    }
    
    public function delete($id, $action) {
        try {
            $building = $this->buildingMapper->findByUniqueId($id);
        }
        catch(SQLException $ex) {
            echo $ex->getMessage();
            exit();
        }
        
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($action == 'Yes') {
                $this->buildingMapper->delete($building);
            }
            
            return $this->redirectToAction('Index');
        }
        else {
            $this->viewData['menu'] = 'hide';
            return $this->view(new BuildingViewModel($building));
        }
    }
    
    public function edit($id, $name, $category, $infolink, $adress, $longitude, $latitude, $movie, $description, $action) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($action == 'Save') {
                $location = new Location($longitude, $latitude, $adress);
                $category = $this->categoryMapper->findByUniqueId($category);
                
                try {
                    $movie = $this->movieMapper->findByUniqueId($movie);
                }
                catch(SQLException $ex) {
                    $movie = null;
                }
                
                try {
                    $building = $this->buildingMapper->findByUniqueId($id);
                    $building->setName($name);
                    $building->setCategory($category);
                    $building->setInfoLink($infolink);
                    $building->setLocation($location);
                    $building->setMovie($movie);
                    $building->setDescription($description);
                    
                    $this->buildingMapper->update($building);
                }
                catch(SQLException $ex) {
                    $building = new Building($name, $description, $infolink, 0, $location, $category, $movie);
                    
                    $this->buildingMapper->create($building);
                }
            }
            return $this->redirectToAction('Index');    
        }
        else {
            $editViewModel = new EditViewModel();
            
            foreach($this->categoryMapper->findAllObjects() as $category) {
                $editViewModel->addCategoryViewModel(new CategoryViewModel($category));
            }
            
            foreach($this->movieMapper->findAllObjects() as $movie) {
                $editViewModel->addMovieViewModel(new MovieViewModel($movie));
            }
            
            try {
                $building = $this->buildingMapper->findByUniqueId($id);
                
                $editViewModel->setBuildingViewModel(new BuildingViewModel($building));
            }
            catch(SQLException $ex) {

            }
            $this->viewData['menu'] = 'hide';
            return $this->view($editViewModel);            
        }
    }

    public function generateQR($id) {
        $building = $this->buildingMapper->findByQrToken($id);
        
        return $this->file("http://qr.kaywa.com/?s=8&d=" . Config::$SERVER .  "?token=" . $id, "image/png", strtolower($building->getName()) . "-qr.png");
    }
    
    public function upload($id, $file, $name, $action) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($action == 'Upload') {
                try {
                    $this->uploadFile($name);
                }
                catch(Exception $ex) {
                    $this->viewData['error'] = $ex->getMessage();
                    
                    $this->viewData['id'] = $id;
                    $this->viewData['name'] = $name;
                    
                    return $this->view('Upload');
                }
            }
            
            $routeValueDictionary = new RouteValueDictionary();
            $routeValueDictionary->add('controller', 'Admin');
            $routeValueDictionary->add('id', $id);
            
            return $this->redirectToAction('Edit', $routeValueDictionary);
        }
        else {
            $this->viewData['id'] = $id;
            $this->viewData['menu'] = 'hide';
            return $this->view();
        }
    }
    
    private function uploadFile($name) {
        if(trim($name) == '') {
            throw new Exception("The name can not be left empty.");
        }
        
        $target_path = "content/movie/";
        $fileName = preg_replace("/[^a-z0-9]+/i", "-", $name) . ".3gp";
        
        $target_path = $target_path . $fileName;
        
        if(file_exists($target_path)) {
            throw new Exception("The filename '" . $name . "' you provided allready exists.");
        }
        
        $extension = strtolower(substr($_FILES['file']['name'], strlen($_FILES['file']['name'])-3));
    
        if($extension != '3gp') {
            throw new Exception("The file you provided has the wrong extension. You can only upload 3gp movies.");
        }

        if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
            $token = base64_encode(date('YmdHi') . $fileName);
            
            $movie = new Movie($fileName, $token);
            
            $this->movieMapper->create($movie);
            
            echo $movie->getId();
        } 
        else {
            throw new Exception("There was an error uploading the file, please try again!");
        }
    }

    /**
     * The implemenation tells the framework if the user is authorized.
     * 
     * @return  boolean     True if the user is authorized, otherwhise false
     */
    public function authorize() {        
        if(isset($_SESSION[Config::$SESSION_NAME])) {
            $user = unserialize($_SESSION[Config::$SESSION_NAME]);
                        
            return $user->getIp() == $_SERVER['REMOTE_ADDR'];
        }
        
        return false;
    }
    
    /**
     * The implementation tells the framework what is has to do when authorization fails.
     * 
     * @return  result      An action result has to be returned.
     */
    public function onAuthenticationError() {
        $routeValueDictionary = new RouteValueDictionary();
        $routeValueDictionary->add('controller', 'Logon');
        
        return $this->redirectToAction('Index', $routeValueDictionary);
    }
}
?>