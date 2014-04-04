<?php
return array(
    'router' => array(
        'routes' => array(
            'wdg-blog' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/blog',
                    'defaults' => array(
                        'controller' => 'WdgBlog\Controller\Blog',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'post' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/post[/:slug]',
                            'constraints' => array(
                                'slug' => '[a-zA-Z0-9_-]+'
                            ),
                            'defaults' => array(
                                'controller' => 'WdgBlog\Controller\Blog',
                                'action' => 'post'
                            )
                        ),
                    ),
                    'category' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/category[/:slug][/:page]',
                            'constraints' => array(
                                'slug' => '[a-zA-Z0-9_-]+'
                            ),
                            'defaults' => array(
                                'page' => 1,
                                'controller' => 'WdgBlog\Controller\Blog',
                                'action' => 'category'
                            )
                        ),
                    ),
                    'contributor' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/contributor/[:id]',
                            'defaults' => array(
                                'controller' => 'WdgBlog\Controller\Contributor',
                                'action' => 'index'
                            )
                        ),
                        'may_terminate' => true,
                    )
                )
            ),
            'zfcadmin' => array(
                'child_routes' => array(
                    'wdg-blog-admin' => array(
                        'type' => 'Literal',
                        'priority' => 1000,
                        'options' => array(
                            'route' => '/blog',
                            'defaults' => array(
                                'controller' => 'WdgBlog\Controller\BlogAdmin',
                                'action'     => 'index'
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'post' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/post'
                                ),
                                'child_routes' => array(
                                    'show' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/[:id]',
                                            'defaults' => array(
                                                'controller' => 'WdgBlog\Controller\BlogAdminPost',
                                                'action' => 'show'
                                            )
                                        ),
                                        'may_terminate' => true,                                        
                                        'priority' => 100,
                                    ),
                                    'list' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/list[/:page]',
                                            'defaults' => array(
                                                'controller' => 'WdgBlog\Controller\BlogAdminPost',
                                                'action' => 'list',
                                                'page' => '1'
                                            )
                                        ),
                                        'may_terminate' => true,                                        
                                        'priority' => 1000,
                                    ),
                                    'add' => array(
                                        'type' => 'Literal',
                                        'options' => array(
                                            'route' => '/add',
                                            'defaults' => array(
                                                'controller' => 'WdgBlog\Controller\BlogAdminPost',
                                                'action' => 'add'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 1000,
                                    ),
                                    'delete' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/delete[/:id]',
                                            'defaults' => array(
                                                'controller' => 'WdgBlog\Controller\BlogAdminPost',
                                                'action' => 'delete'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 1000,
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit[/:id]',
                                            'defaults' => array(
                                                'controller' => 'WdgBlog\Controller\BlogAdminPost',
                                                'action' => 'edit'
                                            )
                                        ),
                                        'priority' => 1000,
                                        'may_terminate' => true,
                                    ),
                                    'categories' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/categories[/:id]',
                                            'defaults' => array(
                                                'controller' => 'WdgBlog\Controller\BlogAdminPost',
                                                'action' => 'categories'
                                            )
                                        ),
                                        'priority' => 1000,
                                        'may_terminate' => true,
                                    ),
                                ),
                            ),
                            'category' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/category',
                                ),
                                'child_routes' => array(
                                    'show' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '[/:id]',
                                            'defaults' => array(
                                                'controller' => 'WdgBlog\Controller\BlogAdminCategory',
                                                'action' => 'show'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 10,
                                    ),
                                    'list' => array(
                                        'type' => 'Literal',
                                        'options' => array(
                                            'route' => '/list',
                                            'defaults' => array(
                                                'controller' => 'WdgBlog\Controller\BlogAdminCategory',
                                                'action' => 'list'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 1000,
                                    ),
                                    'add' => array(
                                        'type' => 'Literal',
                                        'options' => array(
                                            'route' => '/add',
                                            'defaults' => array(
                                                'controller' => 'WdgBlog\Controller\BlogAdminCategory',
                                                'action' => 'add'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 1000,
                                    ),
                                    'delete' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/delete[/:id]',
                                            'defaults' => array(
                                                'controller' => 'WdgBlog\Controller\BlogAdminCategory',
                                                'action' => 'delete'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 1000,
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit[/:id]',
                                            'defaults' => array(
                                                'controller' => 'WdgBlog\Controller\BlogAdminCategory',
                                                'action' => 'edit'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 1000,
                                    ),
                                )
                            )
                        )
                    )
                )
            )
        )
    )
);