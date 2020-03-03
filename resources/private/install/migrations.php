<?php

require 'vendor/autoload.php';

return [
    'name' => 'OmniTools/Migrations',
    'migrations_namespace' => 'OmniTools\Migrations',
    'table_name' => 'migrations',
    'column_name' => 'version',
    'column_length' => 14,
    'executed_at_column_name' => 'executed_at',
    'migrations_directory' => 'vendor/omnitools/core/src/Migrations/',
    'all_or_nothing' => true,
    'check_database_platform' => true
];
