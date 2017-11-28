<?php

/**
 * Required application config
 */
$config = [];

/**
 * Application base url
 */
$config['base_url'] = 'http://localhost:8080';

/**
 * Database config
 */
$config['database'] = require 'database.config.php';

/**
 * API routes configuration
 */
$config['routes'] = require 'routes.config.php';

return $config;
