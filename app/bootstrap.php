<?php
use Dotenv\Dotenv;
use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Acclimate\Container\CompositeContainer;
use Acclimate\Container\ContainerAcclimator;
use Symfony\Component\DependencyInjection\ContainerBuilder as DiCBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config as ServiceManagerConfig;

$loader = require __DIR__.'/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__ . '/../');
$dotenv->load();

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

// Global container
$container = new CompositeContainer();

// Symfony container
$sfContainer = new DicBuilder();
$loader = new XmlFileLoader($sfContainer, new FileLocator(realpath(__DIR__  . '/../')));
$loader->load(realpath(__DIR__ . '/../configs/services.xml'));
//$sfContainer->setParameter("parameter_name", "parameter_value");

$acclimate = new ContainerAcclimator;
$container = new CompositeContainer();

$container->addContainer($acclimate->acclimate($sfContainer));

// Annotation Container
$builder = new ContainerBuilder();
$builder->useAnnotations(true);
$builder->wrapContainer($container);
$phpdiContainer = $builder->build();

$container->addContainer($acclimate->acclimate($phpdiContainer));

// Zend Framework Container
$conf = include __DIR__ . '/../configs/services.php';
$serviceManagerConfigurator = new ServiceManagerConfig($conf["services"]);

$serviceManager = new ServiceManager();
$serviceManagerConfigurator->configureServiceManager($serviceManager);
$serviceManager->setService("Config", $conf);

$container->addContainer($acclimate->acclimate($serviceManager));

