<?php
    namespace Controllers;

    use Symfony\Component\HttpFoundation;
    use Symfony\Component\Routing;
    use Twig;

    $routes = include __DIR__ . '/../routes.php';



    class BaseController {
        protected $templates_loader;
        protected $twig; 

        public function __construct() {
            $this->templates_loader = new Twig\Loader\FilesystemLoader('src/templates');

            $this->twig = new Twig\Environment($this->templates_loader);
        }

        protected function generateTemplateResponse(string $path, array $context = []): HttpFoundation\Response {
            return new HttpFoundation\Response($this->twig->render($path, $context));
        }

        protected function generateRedirectResponse(HttpFoundation\Request $request, string $routeName, array $context = [], int $statusCode = 301): HttpFoundation\RedirectResponse {
            $request_context = new Routing\RequestContext();
            $request_context->fromRequest($request);

            global $routes;
            $url_generator = new Routing\Generator\UrlGenerator($routes, $request_context);

            return new HttpFoundation\RedirectResponse($url_generator->generate($routeName, $context), $statusCode);
        }
    }
?>