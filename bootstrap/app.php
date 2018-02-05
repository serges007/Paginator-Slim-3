<?php

use App\View\Factory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Pagination\Paginator;

require __DIR__ . '/../vendor/autoload.php';

LengthAwarePaginator::viewFactoryResolver(function(){
    return new Factory;
});

LengthAwarePaginator::defaultView('pagination/paginator.twig'); 

Paginator::currentPathResolver(function () {
    return isset($_SERVER['REQUEST_URI']) ? strtok($_SERVER['REQUEST_URI'], '?') : '/';
});

Paginator::currentPageResolver(function () {
    return isset($_GET['page'])? $_GET['page'] : 1;
});

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'cart',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();


$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ]
]);

$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = Factory::getEngine();
    
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['db'] = function () {
    return new PDO('mysql:host=localhost;dbname=cart', 'root', '');
};

require __DIR__ . '/../routes/web.php';
