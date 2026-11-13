<?php
declare(strict_types=1);

use Course\Http\Request;
use Course\Http\Router;
use Course\Controller\AccountController;
use Course\Controller\AccountCatalogController;

return static function (
    Router $router,
    AccountController $accounts,
    AccountCatalogController $catalog
): void {

    $router->add('GET', '/', fn(Request $request) => $catalog->listing($request));
    $router->add('GET', '/items', fn(Request $request) => $catalog->listing($request));
    $router->add('GET', '/items/new', fn() => $catalog->newForm());
    $router->add('GET', '/items/{id}', fn(Request $request, $id) => $catalog->show((int) $id));
    $router->add('POST', '/items', fn(Request $request) => $catalog->save($request));

    $router->add('GET', '/items/{id}/edit', fn(Request $request, $id) => $catalog->editForm((int) $id));
    $router->add('POST', '/items/{id}/edit', fn(Request $request, $id) => $catalog->save($request, (int) $id));
    $router->add('POST', '/items/{id}/delete', fn(Request $request, $id) => $catalog->delete($request, (int) $id));

    $router->add('GET', '/register', fn() => $accounts->form(true));
    $router->add('GET', '/login', fn() => $accounts->form(false));

    $router->add('POST', '/register', fn(Request $request) => $accounts->register($request));
    $router->add('POST', '/login', fn(Request $request) => $accounts->login($request));
    $router->add('POST', '/logout', fn(Request $request) => $accounts->logout($request));
};
