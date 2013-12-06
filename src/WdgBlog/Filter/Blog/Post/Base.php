<?php
namespace WdgBlog\Filter\Blog\Post;

use Zend\InputFilter\InputFilter;

class Base extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty', 
                    'break_chain_on_failure' => true,  
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Title is required'
                        ),
                    )
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 200,
                        'messages' => array(
                            'stringLengthTooLong' => 'Title is too long. 200 characters maximum'
                        )
                    ),
                )
            ),
        ));
        
        $this->add(array(
            'name' => 'slug',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty', 
                    'break_chain_on_failure' => true,  
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Slug is required'
                        ),
                    )
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 200,
                        'messages' => array(
                            'stringLengthTooLong' => 'Slug is too long. 200 characters maximum'
                        )
                    ),
                )
            ),
        ));
        
        $this->add(array(
            'name' => 'thumbnail',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            )
        ));
        
        $this->add(array(
            'name' => 'thumbnail_alt',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            )
        ));
        
        $this->add(array(
            'name' => 'body',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty', 
                    'break_chain_on_failure' => true,  
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Body is required'
                        ),
                    )
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100000,
                        'messages' => array(
                            'stringLengthTooLong' => 'Body is too long. 100000 characters maximum'
                        )
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'excerpt',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty', 
                    'break_chain_on_failure' => true,  
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Excerpt is required'
                        ),
                    )
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 10000,
                        'messages' => array(
                            'stringLengthTooLong' => 'Excerpt is too long. 10000 characters maximum'
                        )
                    ),
                ),
            ),
        ));
    }
}

