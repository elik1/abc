<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
	'controllers' => array(
        'invokables' => array(
            'Family\Controller\Account' => 'Family\Controller\AccountController',
			'Family\Controller\Transaction' => 'Family\Controller\TransactionController',
			'Family\Controller\Log' => 'Family\Controller\LogController',
		),
    ), 
    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /family/:controller/:action
            'family' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/family',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Family\Controller',
                        'controller'    => 'Account',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
					'list' => array(
                        'type'    => 'Segment',
                        'options' => array (
                            'route' => '/account/list[/:page]',
                            'constraints' => array(
                                'page'     => '[0-28]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'Account',
                                'action'        => 'list',
                                'page'          => '1',
                            ),
                        )
                    )
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' =>array(
			'database' 	       => 'Family\Service\Factory\Database',
			'auth' 	       => 'Family\Service\Factory\Authentication',
			'acl'	       => 'Family\Service\Factory\Acl',
			'user'	       => 'Family\Service\Factory\User',
		),
		'invokables' => array(
            'table-gateway'     => 'Family\Service\Invokable\TableGateway',
			'auth-adapter' 	=> 'Family\Authentication\Adapter',
        ),
    ),
	'table-gateway' => array(
        'map' => array(
            'users' => 'Family\Model\User',  
        )
    ),
	'acl' => array(
        'role' => array (
                'guest'   => null,
                'member'  => null,
                'admin'   => array('member'),
        ),
        'resource' => array (
                'account'     => null,
                'log'         => null,
				'transaction' => null,
        ),
        'allow' => array (
                array('guest', 'log', 'in'),
                array('member', 'account', array('me')),  
                array('member', 'transaction', array('list')),
				array('member', 'log', 'out'),  
                array('admin', 'account', null),  
				array('admin', 'transaction', null),
        ),
        'deny'  => array (
                array('guest', null, 'delete')  
        ),
        'defaults' => array (
                'guest_role' => 'guest',
                'member_role' => 'member',
        ),
        'resource_aliases' => array (
                'Family\Controller\Account' => 'account',
        ),
        'modules' => array (
                'Family',
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
     
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
	'navigation' => array(
        'default' => array(
            array(
                'label' => 'Families',
                'route' => 'family/default',
                'pages' => array(
                        array(
                            'label' => 'Add',
                            'route' => 'family/default',
                            'controller' => 'account',
                            'action' => 'add',
                            'resource' => 'account',
                            'privilege' => 'add',
                        ),
                        array(
                            'label' => 'View',
                            'route' => 'family/default',
                            'controller' => 'account',
                            'action' => 'list',
                            'resource' => 'account',
                            'privilege' => 'list',
                        ),
                        array(
                            'label' => 'Edit',
                            'route' => 'family/default',
                            'controller' => 'account',
                            'action' => 'edit',
                            'resource' => 'account',
                            'privilege' => 'edit',
                        ),
                        array(
                            'label' => 'Delete',
                            'route' => 'family/default',
                            'controller' => 'account',
                            'action' => 'delete',
                            'resource' => 'account',
                            'privilege' => 'delete',
                        ),
                )
            ),
			array(
                'label' => 'Log',
                'route' => 'family/default',
                'controller'=> 'log',
                'pages' => array(
						array(
								'label' => 'My account',
								'route' => 'family/default',
								'controller' => 'account',
								'action' => 'me',
								'resource' => 'account',
								'privilege' => 'me',
						), 
						array(
                            'label' => 'Log in',
                            'route' => 'family/default',
                            'controller' => 'log',
                            'action'    => 'in',
                            'resource'  => 'log',
                            'privilege' => 'in'
                        ),
                        array(
                            'label' => 'Log out',
                            'route' => 'family/default',
                            'controller' => 'log',
                            'action'    => 'out',
                            'resource'  => 'log',
                            'privilege' => 'out'
                        ),
                )
            ),
			array(
                'label' => 'Transactions',
                'route' => 'family/default',
                'controller'=> 'log',
                'pages' => array(
						array(
								'label' => 'View',
								'route' => 'family/default',
								'controller' => 'transaction',
								'action' => 'list',
								'resource' => 'transaction',
								'privilege' => 'list',
						), 
                )
            )
        )
    ),
);
