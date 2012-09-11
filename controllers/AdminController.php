<?php
require_once('system/web/mvc/Controller.php');

require_once('models/DAL/BuildingMapper.php');
require_once('models/DAL/CategoryMapper.php');

require_once('viewmodels/BuildingViewModel.php');
require_once('viewmodels/CategoryViewModel.php');

class AdminController extends Controller {
    
    private $buildingMapper;
    private $categoryMapper;

    public function __construct() {
        parent::__construct();
        
        $this->buildingMapper = new BuildingMapper();
        $this->categoryMapper = new CategoryMapper();
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
    
    public function edit($id) {
        try {
            $building = $this->buildingMapper->findByUniqueId($id);
        }
        catch(SQLException $ex) {
            echo $ex->getMessage();
            exit();
        }
        
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
        }
        else {
 
            return $this->view(new BuildingViewModel($building));
        }
    }
}
?>