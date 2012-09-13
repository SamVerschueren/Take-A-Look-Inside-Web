<?php
require_once('system/web/mvc/Controller.php');
require_once('system/web/mvc/Authorizable.php');

require_once('models/DAL/BuildingMapper.php');
require_once('models/DAL/CategoryMapper.php');

require_once('models/domain/User.php');

require_once('viewmodels/BuildingViewModel.php');
require_once('viewmodels/CategoryViewModel.php');

/**
 * This is the controller that handles all the admin actions. It implements Authorizable because you have to be authorized
 * to do any kind of admin action.
 * 
 * @package controllers
 * @since 2012-09-11
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
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
    
    /**
     * The index function when the user navigates to /Admin/Index
     * 
     * @return  viewResult      The view can be found in views/admin/map.phtml
     */
    public function index() {
        $this->viewData['title'] = 'Admin - Take A Look Inside';
        
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
    
    /**
     * The method that deletes a building out of the database. When the request method is GET, it returns a confirmation message.
     * When the request is a POST, it means yes or cancel.
     * 
     * @param   id                  The id of the building that has to be deleted.
     * @param   action              The action of the user, yes or cancel.
     * @return  ActionResult        The action result that can be executed.
     */
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
<<<<<<< HEAD
            $this->viewData['title'] = 'Admin - Take A Look Inside';
            
=======
            $this->viewData['menu'] = 'hide';
>>>>>>> e119fb37afdc48edb3d118180d0a954cb183e437
            return $this->view(new BuildingViewModel($building));
        }
    }
    
    /**
     * The method for editing a building. When the user goes here by a GET request, you will see a viewresult. When you go here by
     * a POST request, the building will be editted in the database.
     * 
     * @param   id                  The id of the building that has to be edited.
     * @param   name                The name of the building.
     * @param   category            The categoryid of the building.
     * @param   infoLink            The link to more information of the building.
     * @param   adress              The adress of the building.
     * @param   longitude           The longitude of the location of the building.
     * @param   latitude            The latitude of the location of the building.
     * @param   movie               The movieid of the building.
     * @param   description         The description of the building.
     * @param   action              The action of the user, yes or cancel.
     * @return  ActionResult        The action result that can be executed.      
     */
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
<<<<<<< HEAD
 
            $this->viewData['title'] = 'Admin - Take A Look Inside';
 
=======
            $this->viewData['menu'] = 'hide';
>>>>>>> e119fb37afdc48edb3d118180d0a954cb183e437
            return $this->view($editViewModel);            
        }
    }

    /**
     * This method will make sure the browser downloads the QR code as en image/png.
     * 
     * @param   id                  The QR token of the movie.
     * @return  FileResult          The file result that can be executed.
     *     
     */
    public function generateQR($id) {
        $building = $this->buildingMapper->findByQrToken($id);
        
        return $this->file("http://qr.kaywa.com/?s=8&d=" . Config::$SERVER .  "?token=" . $id, "image/png", strtolower($building->getName()) . "-qr.png");
    }
    
    /**
     * This method let's the user upload a movie.
     * 
     * @param   id                  The id of the returnURL.
     * @param   name                The name of the file.
     * @param   action              The action of the user, upload or cancel.             
     */
    public function upload($id, $name, $action) {
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
            $this->viewData['title'] = 'Admin - Take A Look Inside';
            $this->viewData['id'] = $id;
            $this->viewData['menu'] = 'hide';
            return $this->view();
        }
    }
    
    /**
     * This method will actually upload the file.
     * 
     * @param   name        The name of the file.
     */
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