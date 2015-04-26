<?php
namespace App\Factory;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RepositoryFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return (preg_match("/Repository$/", $requestedName)) ? true : false;
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $manager = $serviceLocator->get("orm");

        $matched = [];
        preg_match("/(.*)Repository$/", $requestedName, $matched);

        $repo = $manager->getRepository("App\\Entity\\".ucfirst($matched[1]));

        return $repo;
    }
}
