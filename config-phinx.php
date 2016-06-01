<?php
require 'src/settings.php';
return [
  'paths' => [
    'migrations' => 'migrations'
  ],
  'migration_base_class' => '\MyProject\Migration\Migration',
  'environments' => [
    'default_migration_table' => 'phinxlog',
    'default_database' => 'dev',
    'dev' => [
      'adapter' => 'mysql',
      'host' => 'localhost',
      'name' => 'countytrace',
      'user' => 'root',
      'pass' => '',
      'port' => '3306'
    ]
  ]
];
