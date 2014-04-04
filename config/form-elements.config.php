<?php
use WdgBlog\Form;

return array(
    'factories' => array(
        'wdgblog_post_add_form' => function(\Zend\Form\FormElementManager $sm){
            $UserService    = $sm->getServiceLocator()->get("zfcuseradmin_mapper");
            $form           = new Form\Blog\Post\Add($UserService);
            
            $form->setInputFilter(new \WdgBlog\Filter\Blog\Post\Add());

            return $form;
        },
        'wdgblog_post_edit_form' => function(\Zend\Form\FormElementManager $sm){
            $UserService    = $sm->getServiceLocator()->get("zfcuseradmin_mapper");
            $form           = new Form\Blog\Post\Edit($UserService);
            
            $form->setInputFilter(new \WdgBlog\Filter\Blog\Post\Edit());

            return $form;
        },
        'wdgblog_category_add_form' => function(){
            
            $form = new Form\Blog\Category\Add();
            
            $form->setInputFilter(new \WdgBlog\Filter\Blog\Category\Add());

            return $form;
        },
        'wdgblog_category_edit_form' => function(){
            $form = new Form\Blog\Category\Edit();
            
            $form->setInputFilter(new \WdgBlog\Filter\Blog\Category\Edit());

            return $form;
        },
        'wdgblog_post_category_form' => function(\Zend\Form\FormElementManager $sm){
            $BlogService    = $sm->getServiceLocator()->get("wdgblog_service_blog");
            $form           = new Form\Blog\Post\Category($BlogService->getAllCategories());
            
            $form->setInputFilter(new \WdgBlog\Filter\Blog\Post\Category());

            return $form;
        },
    )
);