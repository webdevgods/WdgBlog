<?php
namespace WdgBlog\Form\Blog\Post;

use WdgBase\Form\PostFormAbstract;

class Base extends PostFormAbstract
{
    public function __construct(\WdgUser\Service\User $User)
    {
        parent::__construct();

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'author_id',
            'options' => array(
                'label' => 'Author',
                'value_options' => $User->getUsersSelectArray()
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
            'name' => 'thumbnail',
            'options' => array(
                'label' => 'Thumbnail Url',
            ),
        ));
        
        $this->add(array(
            'name' => 'thumbnail_alt',
            'options' => array(
                'label' => 'Thumbnail Alt',
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