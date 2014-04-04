<?php
namespace WdgBlog\Form\Blog\Category;

use WdgZf2\Form\PostFormAbstract;

class Base extends PostFormAbstract
{
    public function __construct()
    {
        parent::__construct();
        
        $this->add(array(
            'name' => 'name',
            'options' => array(
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'name' => 'slug',
            'options' => array(
                'label' => 'Slug',
            ),
        ));
    }
}