<?php
namespace WdgBlog\Filter\Blog\Post;

use Zend\InputFilter\InputFilter;

class Category extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'id',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty', 
                    'break_chain_on_failure' => true,  
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Id is required'
                        ),
                    )
                )
            ),
        ));
        
        $this->add(array(
            'name' => 'categories',
            'required' => false,
            'validators' => array(
            ),
        ));
    }
}

