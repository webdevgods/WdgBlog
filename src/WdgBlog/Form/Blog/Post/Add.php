<?php
namespace WdgBlog\Form\Blog\Post;

class Add extends Base
{
    public function __construct(\WdgUser\Service\User $User)
    {
        parent::__construct($User);
    }
}