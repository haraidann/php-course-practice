<?php
declare(strict_types=1);

use Course\Controller\ItemController;
use Course\Http\Application;
use Course\Http\Request;
use Course\Http\Response;
use Course\Http\Router;
use Course\Repository\PdoItemRepository;

return static function (array $variant, PDO $pdo): Application {
    $router = new Router();
    $repository = new PdoItemRepository($pdo);
    $controller = new ItemController($repository, $variant['categories']);

    $router->add('GET', '/', fn(Request $request) => $controller->listing($request));    
    $router->add('GET', '/items', fn(Request $request) => $controller->listing($request));    
    $router->add('GET', '/items/new', fn() => $controller->newForm());
    $router->add('GET', '/items/{id}', fn(Request $request, $id) => $controller->show((int) $id));
    $router->add('POST', '/items', fn(Request $request) => $controller->create($request));
    
    // TODO P04-COMPOSITION: Создать контроллер с зависимостями и зарегистрировать пять маршрутов.
    return new Application($router);
};
