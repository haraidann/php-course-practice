<?php
declare(strict_types=1);
use Course\Api\{Catalog, Login};
use Course\Http\{Router, Request, Response};
return static function(Router $router, Catalog $catalog, Login $login): void {

    $router->add('GET', '/items/new', fn() => $catalog->newForm());
    $router->add('GET', '/items/{id}', fn(Request $request, $id) => $catalog->show((int) $id));
    $router->add('POST', '/items', fn(Request $request) => $catalog->save($request));

    $router->add('GET', '/items/{id}/edit', fn(Request $request, $id) => $catalog->editForm((int) $id));
    $router->add('POST', '/items/{id}/edit', fn(Request $request, $id) => $catalog->save($request, (int) $id));
    $router->add('POST', '/items/{id}/delete', fn(Request $request, $id) => $catalog->delete($request, (int) $id));

    
    $router->add('GET', '/api/items', fn(Request $request) => $catalog->listing($request));
    $router->add('POST', '/api/login', fn(Request $request) => $login($request));
    $router->add('GET', '/api/items', fn(Request $request) => $catalog->listing($request));
    $router->add('GET', '/api/items/{id}', fn(Request $request, $id) => $catalog->show((int) $id));
    $router->add('POST', '/api/items', fn(Request $request) => $catalog->create($request));
    $router->add('DELETE', '/api/items/{id}', fn(Request $request, $id) => $catalog->delete($request, (int) $id));    
    $router->add('PUT', '/api/items/{id}', fn(Request $request, $id) => $catalog->update($request, (int) $id));
    
    //throw new \RuntimeException('TODO P06-ROUTES');
};
