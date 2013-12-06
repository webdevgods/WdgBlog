<?php
return array(
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
);
