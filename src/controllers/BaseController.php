<?php
    namespace Controllers;

    use Symfony\Component\HttpFoundation;
    use Twig;



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
    }
?>