# Frankie Api

## Introduction

An example of Frankie mixing other projects:

 * Doctrine ORM
 * Symfony2 DiC
 * Zend Framework Service Container
 * Symfony2 Serializer

Zend Service Manager uses fallback strategies in order to autoload Doctrine
Entity Repositories

```php
/**
 * @Inject("userRepository")
 */
private $userRepository;
```

Fallback factory example:

```php
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
```

## Conf

You can use environment variables or a `.env` file to save your configuration.
Create a simple `.env` file in the project root

```
APP_DEBUG=true
APP_CACHE_FOLDER="/tmp/frankie-api-cache"
APP_DATABASE_PATH="/tmp/frankie-api-db.sqlite"
```

You can access to your environment variable via the global `$_ENV` variable

```php
$something->setDebugFlag($_ENV["APP_DEBUG"]);
```

## Create/Drop schema

Create the database

```sh
./vendor/bin/doctrine orm:schema-tool:create
```

Drop it:

```sh
./vendor/bin/doctrine orm:schema-tool:drop --force
```


**This project is just an example do not use**

## Add users

```
curl -XPOST -d '{"name": "Your Name"}' http://localhost:8080/v1/user
```

To check validation

```
curl -v -XPOST -d '{"name": "a"}' http://localhost:8080/v1/user
```

