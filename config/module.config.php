<?php
return array(
    'wdgblog' => array(
        'thumbnailImageTag' => 'blog-thumbnail'
    ),
    'doctrine' => array(
        'driver' => array(
            'WdgBlog_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/WdgBlog/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'WdgBlog\Entity' => 'WdgBlog_driver'
                )
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'module_layouts' => array(
        'WdgBlog' => 'application/layout/layout',
    ),
    'navigation' => array(
        'admin' => array(
            'wdgblog' => array(
                'label' => 'Blog',
                'route' => 'zfcadmin/wdg-blog-admin'
            ),
        ),
    ),
);
