<?php
/**
 * Represents a node in a Tree structure.
 * 
 * @package system.collections.generic
 * @since 2012-08-01
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class TreeNode {

    private $data;
    private $parent;
    private $children = array();

    /**
     * Creates a new instance of a TreeNode
     * 
     * @param   data        The data of this TreeNode.
     */
    public function __construct($data) {
        $this->setData($data);
    }
    
    /**
     * Sets the data for this TreeNode.
     * 
     * @param   data        The value of this TreeNode.
     */
    public function setData($data) {
        $this->data = $data;
    }
    
    /**
     * Gets the value of this TreeNode.
     * 
     * @return  data        The data of this TreeNode.
     */
    public function getData() {
        return $this->data;
    }
    
    /**
     * Sets the parent TreeNode of this TreeNode.
     * 
     * @param   parent      The parent TreeNode.
     */
    public function setParent(TreeNode $parent) {        
        $this->parent = $parent;
    }
    
    /**
     * Gets the parent of this TreeNode.
     * 
     * @return  parent      The parent of this TreeNode.
     */
    public function getParent() {
        return $this->parent;
    }
    
    /**
     * Adds a child TreeNode to this TreeNode.
     * 
     * @param   child       The child of this TreeNode that has to be added.
     */
    public function addChild(TreeNode $child) {
        $child->setParent($this);
        
        $this->children[] = $child;
    }
    
    /**
     * Gets all the child TreeNodes of this TreeNode.
     * 
     * @return  children    An array of TreeNodes.
     */
    public function getChildren() {
        return $this->children;
    }
}
?>