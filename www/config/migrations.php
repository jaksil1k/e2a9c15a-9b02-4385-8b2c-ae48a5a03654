<?php 

use Doctrine\Migrations\Configuration\ConfigurationArray;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray as MigrationConfigurationArray;

return new MigrationConfigurationArray([
    'table_storage' => [
        'table_name' => 'doctrine_migration_versions',
    ],
    'migrations_paths' => [
        'App\Migrations' => './src/Migrations',
    ],
    'all_or_nothing' => true,
    'check_database_platform' => true,
]);