<?php
    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/src/services.php';

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel;
    use Symfony\Component\Routing;



    // create any missing directories
    $pdf_folder = __DIR__ . '/static/pdfs';

    if (is_dir($pdf_folder) == false) {
        mkdir($pdf_folder, 0777, true);
    }

    // handle routing
    $routes = include __DIR__ . '/src/routes.php';

    $request = Request::createFromGlobals();

    $context = new Routing\RequestContext();
    $context->fromRequest($request);

    $matcher = new Routing\Matcher\UrlMatcher($routes, $context);

    $controller_resolver = new HttpKernel\Controller\ControllerResolver();
    $argument_resolver = new HttpKernel\Controller\ArgumentResolver();

    try {
        $request->attributes->add($matcher->match($request->getPathInfo()));

        $controller = $controller_resolver->getController($request);
        $arguments = $argument_resolver->getArguments($request, $controller);

        $response = call_user_func_array($controller, $arguments);
    } 
    catch (Routing\Exception\ResourceNotFoundException $exception) {
        $response = new Response('Not Found', 404);
    }
    catch (Exception $exception) {
        $response = new Response('Internal Server Error', 500);
    }

    $response->send();
?>