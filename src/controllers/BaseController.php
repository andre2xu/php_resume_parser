<?php
    namespace Controllers;

    use Twig;



    class BaseController {
        protected $templates_loader;
        protected $twig; 

        public function __construct() {
            $this->templates_loader = new Twig\Loader\FilesystemLoader('src/templates');

            $this->twig = new Twig\Environment($this->templates_loader);
        }
    }
?>