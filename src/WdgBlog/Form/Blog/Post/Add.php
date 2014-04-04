<?php
namespace WdgBlog\Form\Blog\Post;

class Add extends Base
{
    public function __construct(\ZfcUserAdmin\Mapper\UserDoctrine $User)
    {
        parent::__construct($User);
    }
}