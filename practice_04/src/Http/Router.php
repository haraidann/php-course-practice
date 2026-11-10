<?php
declare(strict_types=1);
namespace Course\Http;

final class Router
{
    private array $routes = [];
    public function add(string $method, string $path, callable $handler): void
    {
        // TODO P04-ROUTING: Регистрировать и выбирать маршруты, передавать id, различать 404/405 и поддерживать HEAD.

        $hasId = str_contains($path, '{id}');
        $quoted = preg_quote($path, '#');
        $regexString = str_replace('\{id\}', '(?<id>[1-9]\d{0,8})', $quoted);
        $regex = '#^' . $regexString . '$#';

        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'regex' => $regex,
            'handler' => $handler,
            'hasId' => $hasId
        ];

    }
    public function dispatch(Request $request): Response
    {
        // TODO P04-ROUTING: Регистрировать и выбирать маршруты, передавать id, различать 404/405 и поддерживать HEAD.

         $reqMethod = $request->method;
         $reqPath = $request->path;

         $matchedPath = false;
         $allowedMethods = [];

         foreach($this->routes as $route) {
             if (!preg_match($route['regex'], $reqPath, $matches)) {
                 continue;
             }

             $matchedPath = true;
             $allowedMethods[] = $route['method'];

             if ($route['method'] === 'GET') {
                 $allowedMethods[] = 'HEAD';
             }

             $methodMatches = ($reqMethod === $route['method']) || ($reqMethod === 'HEAD' && $route['method'] === 'GET');

             if ($methodMatches) {
                 if ($route['hasId']) {
                     return ($route['handler'])($request, (int) $matches['id']);
                 }

                 return ($route['handler'])($request);
             }
         }
        
         if ($matchedPath) {
             $uniqueMethods = array_values(array_unique($allowedMethods));
             $allowedHeader = implode(', ', $uniqueMethods);

             throw new HttpError(405, 'method_not_allowed', 'Метод не поддерживается для этого пути', ['Allow' => $allowedHeader]);
         }

         throw new HttpError(404, 'not_found', 'Страница не найдена');
    }
}
