<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
	'session' => array(
		'remember_me_seconds' => 2419200,
		'use_cookies' => true,
		'cookie_httponly' => true,
	),
    'router' => array(
      'routes' => array(
        'home' => array(
          'type' => 'Zend\Mvc\Router\Http\Literal',
          'options' => array(
            'route'    => '/',
            'defaults' => array(
              'controller' => 'RoadInfo\Controller\Index',
              'action'     => 'index',
            ),
          ),
        ),
        'segment' => array(
          'type' => 'Zend\Mvc\Router\Http\Literal',
          'options' => array(
            'route' => '/segment',
            'defaults' => array(
              'controller' => 'RoadInfo\Controller\Segment',
              'action' => 'bla'
            ),
          ),
          'may_terminate' => true,
          'child_routes' => array(
            'index' => array(
              'type' => 'Zend\Mvc\Router\Http\Segment',
              'options' => array(
                'route' => '/:id',
                'constraints' => array(
                  'id' => '[0-9]*',
                ),
                'defaults' => array(
                  'controller' => 'RoadInfo\Controller\Segment',
                  'action' => 'index'
                ),
              )
            ),
            'list' => array(
              'type' => 'Zend\Mvc\Router\Http\Segment',
              'options' => array(
                'route' => '/bla',
                'defaults' => array(
                  'controller' => 'RoadInfo\Controller\Segment',
                  'action' => 'bla'
                ),
              )
            ),
            'update' => array(
              'type' => 'Zend\Mvc\Router\Http\Segment',
              'options' => array(
                'route' => '/:id/update',
                'constraints' => array(
                  'id' => '[0-9]*',
                ),
                'defaults' => array(
                  'controller' => 'RoadInfo\Controller\Segment',
                  'action' => 'update'
                ),
              )
            ),
          ),
        ),
      ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'RoadInfo\Controller\Console' => 'RoadInfo\Controller\ConsoleController',
            'RoadInfo\Controller\Index' => 'RoadInfo\Controller\IndexController',
            'RoadInfo\Controller\Segment' => 'RoadInfo\Controller\SegmentController',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'paragrapher' => 'RoadInfo\View\Helper\Paragrapher',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
		'base_path' => '/roadinfo/',
        'strategies' => array(
            'ViewFeedStrategy',
			'ViewJsonStrategy',
        ),
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
			'layout/landing'           => __DIR__ . '/../view/layout/landing.phtml',
			'layout/anonymous'           => __DIR__ . '/../view/layout/anonymous.phtml',
			'layout/csv'           	  => __DIR__ . '/../view/layout/csv.phtml',
            'roadinfo/index/index' => __DIR__ . '/../view/roadinfo/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
			'error/401'               => __DIR__ . '/../view/error/401.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(),
    ),
);
