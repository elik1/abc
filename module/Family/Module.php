<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Family;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $event)
    {
		$services = $event->getApplication()->getServiceManager();
        $dbAdapter = $services->get('database');
        \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::setStaticAdapter($dbAdapter);
		
		$eventManager = $event->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'protectPage'), -100);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	
	public function protectPage(MvcEvent $event)
    {
        $match = $event->getRouteMatch();
        if(!$match) {
            return;
        }

        $controller = $match->getParam('controller');
        $action     = $match->getParam('action');
        $namespace  = $match->getParam('__NAMESPACE__');

        $parts           = explode('\\', $namespace);
        $moduleNamespace = $parts[0];

        $services = $event->getApplication()->getServiceManager();
        $config = $services->get('config');

        $auth     = $services->get('auth');
        $acl      = $services->get('acl');

        $currentUser = $services->get('user');
        $role = $currentUser->getRole();
		
        \Zend\View\Helper\Navigation\AbstractHelper::setDefaultAcl($acl);
        \Zend\View\Helper\Navigation\AbstractHelper::setDefaultRole($role);

        $aclModules = $config['acl']['modules'];
        if (!empty($aclModules) && !in_array($moduleNamespace, $aclModules)) {
            return;
        }

        $resourceAliases = $config['acl']['resource_aliases'];
        if (isset($resourceAliases[$controller])) {
            $resource = $resourceAliases[$controller];
        } else {
            $resource = strtolower(substr($controller, strrpos($controller,'\\')+1));
        }

        if(!$acl->hasResource($resource)) {
            $acl->addResource($resource);
        }
        try {
            if($acl->isAllowed($role, $resource, $action)) {
                return;
            }
        } catch(AclException $ex) {
             
        }

        $response = $event->getResponse();
        $response->setStatusCode(403);
        $match->setParam('controller', 'Family\Controller\Account');
        $match->setParam('action', 'denied');
    }

}
