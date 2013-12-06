<?php
namespace WdgBlog\Filter\Blog\Category;

class Edit extends Base
{
    public function __construct()
    {        
        parent::__construct();
        
        $this->add(array(
            'name' => 'id',
            'required' => false,
            'filters' => array(
                array('name' => 'Int'),
            ),
        ));
    }
}

