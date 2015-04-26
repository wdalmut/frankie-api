<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

return [
    "services" => [
        "factories" => [
            "App\\Orm" => function($sl) {
                $isDevMode = true;

                $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/../src/Entity"), $isDevMode, __DIR__ . '/../cache', null, false);

                // database configuration parameters
                $conn = array(
                    'driver' => 'pdo_sqlite',
                    'path' => __DIR__ . '/../db.sqlite',
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
