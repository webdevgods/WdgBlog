<?php
namespace WdgBlog;

return array(
    'invokables' => array(
        'wdgblog_service_blog' => 'WdgBlog\Service\Blog'
    ),
    'factories' => array(
        'wdgblog_doctrine_em' => function ($sm) {
            return $sm->get('doctrine.entitymanager.orm_default');
        },
        'wdgblog_module_options' => function ($sm) {
            $config = $sm->get('Config');
            return new Options\ModuleOptions(isset($config['wdgblog']) ? $config['wdgblog'] : array());
        },
        'wdgblog_repos_post' => function ($sm) {
            return $sm->get('wdgblog_doctrine_em')->getRepository("WdgBlog\Entity\Post");
        },
        'wdgblog_repos_category' => function ($sm) {
            return $sm->get('wdgblog_doctrine_em')->getRepository("WdgBlog\Entity\Category");
        }
    )
);