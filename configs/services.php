<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

return [
    "services" => [
        "factories" => [
            "App\\Orm" => function($sl) {
                $isDevMode = $_ENV["APP_DEBUG"];

                $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/../src/Entity"), $isDevMode, __DIR__ . '/../cache', null, false);

                // database configuration parameters
                $conn = array(
                    'driver' => 'pdo_sqlite',
                    'path' => $_ENV["APP_DATABASE_PATH"],
                );

                // obtaining the entity manager
                $entityManager = EntityManager::create($conn, $config);

                return $entityManager;
            },
        ],
        "abstract_factories" => [
            "userRepository" => "App\Factory\RepositoryFactory",
        ],
        "aliases" => [
            "orm" => "App\\Orm",
        ],
    ],
];
