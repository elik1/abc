<?php
namespace Family\Service\Invokable;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway as DbTableGateway;

class TableGateway implements ServiceLocatorAwareInterface
{
     
    protected $cache;

    protected $serviceLocator;

    public function get($tableName, $features=null, $resultSetPrototype=null)
    {
        $cacheKey = $tableName;
      
        if(isset($this->cache[$cacheKey])) {
            return $this->cache;
        }

        $config  = $this->serviceLocator->get('config');
        $tableGatewayMap = $config['table-gateway']['map'];
        if(isset($tableGatewayMap[$tableName])) {
            $className = $tableGatewayMap[$tableName];
            $this->cache[$cacheKey] = new $className();
        } else {
            $db = $this->serviceLocator->get('database');
            $this->cache[$cacheKey] = new DbTableGateway($tableName, $db, $features, $resultSetPrototype);
        }

        return $this->cache[$cacheKey];
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

    }
 
    public function getServiceLocator()
    {
        $this->serviceLocator;
    }

}
