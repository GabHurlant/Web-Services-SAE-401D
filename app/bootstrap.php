<?php

/**
 * Bootstrap file for initializing the application.
 */

// Include Composer's autoloader.
require_once __DIR__ . "/../vendor/autoload.php";

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

// Define the paths where Doctrine will look for entity classes.
$paths = array(__DIR__ . "/src/Entity");

// Whether to enable development mode. Set to true in development environment.
$isDevMode = true;

// Create a Doctrine ORM configuration for entity mapping using annotations.
$config = ORMSetup::createAnnotationMetadataConfiguration($paths, $isDevMode);

// Configure the database connection parameters.
$connection = DriverManager::getConnection([
    'dbname' => 'lasne221_4',
    'user' => 'lasne221',
    'password' => 'EexaetiY6rahphoo',
    'host' => 'mysql.info.unicaen.fr',
    'driver' => 'pdo_mysql',
], $config);

// Obtain the entity manager using the configured database connection and ORM configuration.
$entityManager = new EntityManager($connection, $config);
