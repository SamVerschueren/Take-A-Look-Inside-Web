<?php
class CategoryViewModel {
    
    private $id;
    private $name;
    
    public function __construct(Category $category) {
        $this->setId($category->getId());
        $this->setName($category->getName());
    }
    
    private function setId($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }
    
    private function setName($name) {
        $this->name = $name;;
    }
    
    public function getName() {
        return $this->name;
    }
}
?>