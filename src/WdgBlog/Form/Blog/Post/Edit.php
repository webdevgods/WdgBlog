<?php
namespace WdgBlog\Form\Blog\Post;

class Edit extends Base
{
    public function __construct(\WdgUser\Service\User $User)
    {
        parent::__construct($User);
        
        $this->add(array(
            'type' => 'hidden',
            'name' => 'id',
        ));
    }
}