<?php
require_once('BuildingViewModel.php');
require_once('CategoryViewModel.php');
require_once('MovieViewModel.php');

class EditViewModel {
    
    private $buildingViewModel;
    private $categoryViewModels;
    private $movieViewModels;
    
    public function __construct() {
        $this->categoryViewModels = array();    
        $this->movieViewModels = array();
    }
    
    public function setBuildingViewModel(BuildingViewModel $buildingViewModel) {
        $this->buildingViewModel = $buildingViewModel;
    }
    
    public function getBuildingViewModel() {
        return $this->buildingViewModel;
    }
    
    public function addCategoryViewModel(CategoryViewModel $categoryViewModel) {
        $this->categoryViewModels[] = $categoryViewModel;   
    }
    
    public function getCategoryViewModels() {
        return $this->categoryViewModels;
    }
    
    public function addMovieViewModel(MovieViewModel $movieViewModel) {
        $this->movieViewModels[] = $movieViewModel;
    }
    
    public function getMovieViewModels() {
        return $this->movieViewModels;
    }
}
?>