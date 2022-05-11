<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = \App\Core\App::getInstance();

$app->db->applyMigrations();
