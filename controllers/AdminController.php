<?php
require_once('system/web/mvc/Controller.php');

require_once('models/DAL/BuildingMapper.php');
require_once('models/DAL/CategoryMapper.php');

require_once('viewmodels/BuildingViewModel.php');
require_once('viewmodels/CategoryViewModel.php');

class AdminController extends Controller {
    
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
 
            return $this->view($editViewModel);            
        }
    }

    public function generateQR($id) {
        $building = $this->buildingMapper->findByQrToken($id);
        
        return $this->file("http://qr.kaywa.com/?s=8&d=" . Config::$SERVER .  "?token=" . $id, "image/png", strtolower($building->getName()) . "-qr.png");
    }
}
?>