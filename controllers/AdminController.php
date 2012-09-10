<?php
require_once('system/web/mvc/Controller.php');

require_once('models/DAL/BuildingMapper.php');

require_once('viewmodels/BuildingViewModel.php');

class AdminController extends Controller {
    
    private $buildingMapper;

    public function __construct() {
        parent::__construct();
        
        $this->buildingMapper = new BuildingMapper();
    }
    
    public function index() {
        $buildings = $this->buildingMapper->findAllObjects();
        
        $buildingViewModels = array();
        
        foreach($buildings as $building) {
            $buildingViewModels[] = new BuildingViewModel($building);
        }
        
        return $this->view($buildingViewModels);
    }
}
?>