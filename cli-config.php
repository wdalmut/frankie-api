<?php
use Dotenv\Dotenv;
use DI\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Corley\Middleware\App;
use Acclimate\Container\CompositeContainer;
use Acclimate\Container\ContainerAcclimator;
use Corley\Middleware\Factory\AppFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder as DiCBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config as ServiceManagerConfig;

$loader = include __DIR__.'/vendor/autoload.php';

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$container = new CompositeContainer();

$sfContainer = new DicBuilder();
$loader = new XmlFileLoader($sfContainer, new FileLocator(realpath(__DIR__)));
$loader->load(realpath(__DIR__ . '/configs/services.xml'));

$acclimate = new ContainerAcclimator;
$container = new CompositeContainer();

$container->addContainer($acclimate->acclimate($sfContainer));

$builder = new ContainerBuilder();
$builder->useAnnotations(true);
$builder->wrapContainer($container);
$phpdiContainer = $builder->build();

$container->addContainer($acclimate->acclimate($phpdiContainer));

$conf = include __DIR__ . '/configs/services.php';
$serviceManagerConfigurator = new ServiceManagerConfig($conf["services"]);

$serviceManager = new ServiceManager();
$serviceManagerConfigurator->configureServiceManager($serviceManager);
$serviceManager->setService("Config", $conf);

$container->addContainer($acclimate->acclimate($serviceManager));

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($container->get("orm"));
