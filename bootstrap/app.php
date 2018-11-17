<?php

// use Respect\Validation\Validator as v;

session_start();

require __DIR__ .'/../vendor/autoload.php';

$dotEnv = new Dotenv\Dotenv('../');
$dotEnv->load();

$settings = require __DIR__ .'/../bootstrap/settings.php';

$app = new \Slim\App($settings);


$params = array(
    'database' 	=>	getenv('DB_DATABASE','your_db'),
    'username' 	=>	getenv('DB_USERNAME', 'root'),
    'password' 	=>	getenv('DB_PASSWORD',''),
    'prefix'    => getenv('DB_PREFIX', 'wp_'), // Specify the prefix for WordPress tables, default prefix is 'wp_'
);
Corcel\Database::connect($params);


require __DIR__ .'/../bootstrap/dependencies.php';

require __DIR__ .'/../bootstrap/controllers.php';

require __DIR__ .'/../bootstrap/middlewares.php';

Respect\Validation\Validator::with('App\Validation\Rules');

require __DIR__ .'/../bootstrap/routes.php';
