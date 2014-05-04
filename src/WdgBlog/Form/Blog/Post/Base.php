<?php
namespace WdgBlog\Form\Blog\Post;

use WdgZf2\Form\PostFormAbstract;

class Base extends PostFormAbstract
{
    public function __construct(\ZfcUserAdmin\Mapper\UserDoctrine $User)
    {
        parent::__construct();
        
        $this->setAttribute('enctype','multipart/form-data');
        
        $users = $User->findAll();
        
        $options = array();
        
        foreach($users as $user)
        {
            $options[$user->getId()] = $user->getUserName();
        }

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'author_id',
            'options' => array(
                'label' => 'Author',
                'value_options' => $options
            ),
        ));
        
        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => 'Title',
            ),
        ));

        $this->add(array(
            'name' => 'slug',
            'options' => array(
                'label' => 'Slug',
            ),
        ));
        
        $this->add(array(
            'type' => 'file',
            'name' => 'thumbnail',
            'options' => array(
                'label' => 'Thumbnail',
            ),
        ));
        
        $this->add(array(
            'name' => 'thumbnail_name',
            'options' => array(
                'label' => 'Thumbnail Name',
            ),
        ));
        
        $this->add(array(
            'type' => 'textarea',
            'name' => 'excerpt',
            'options' => array(
                'label' => 'Excerpt',
            ),
            'attributes' => array(
                'class' => 'ezeditor'
            )
        ));
        
        $this->add(array(
            'type' => 'textarea',
            'name' => 'body',
            'options' => array(
                'label' => 'Body',
            ),
            'attributes' => array(
                'class' => 'ezeditor'
            )
        ));
    }
}