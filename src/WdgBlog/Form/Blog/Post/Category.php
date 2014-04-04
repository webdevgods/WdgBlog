<?php
namespace WdgBlog\Form\Blog\Post;

use WdgZf2\Form\PostFormAbstract;

class Category extends PostFormAbstract
{
    protected $_categories = array();
    
    public function __construct($categories)
    {
        parent::__construct();
        
        $this->_categories = $categories;

        $options = array();
        
        foreach($this->_categories as $category)
            $options[$category->getId()] = $category->getName();
        
        if($options)
            $this->add(array(
                'type' => 'Zend\Form\Element\MultiCheckbox',
                'name' => 'categories',
                'options' => array(
                    'label' => 'Categories',
                    'value_options' => $options
                ),
            ));
        else
        {
            $this->add(array(
                'type' => 'Zend\Form\Element\Hidden',
                'name' => 'categories',
                'options' => array(
                    'label' => 'No Categories Available',
                ),
            ));
            
            $this->remove("submit");
        }
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'label' => '',
            ),
        ));
    }
    
    /**
     * @param array $categories
     * @return \WdgBlog\Form\Blog\Post\Category
     */
    public function setCategories($categories)
    {
        $this->_categories = $categories;
                
        return $this;
    }
}