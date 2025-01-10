<?php

namespace Helby\lessons\Routing;
use Helby\lessons\Controllers\CommentsController;
use Helby\lessons\Controllers\ArticlesController;
class Route
{
    public static $ROUTER;

    private array $routes = [];

    public function __construct($databasePath)
    {
        if (self::$ROUTER == null) {
            self::$ROUTER = $this;
            $commentsController = new CommentsController($databasePath);
            $articlesController = new ArticlesController($databasePath);
            $this->add('POST', '/posts/comment', [$commentsController, 'addComment']);
            $this->add('POST', '/articles/add', [$articlesController, 'addArticle']);
            $this->add('GET', '/articles', [$articlesController, 'getArticle']);
        } 
    }

    public function add(string $method, string $uri, callable $callback): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'uri' => $uri,
            'callback' => $callback,
        ];
    }

    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['uri'] === $requestUri) {
                call_user_func($route['callback']);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
    }
}
