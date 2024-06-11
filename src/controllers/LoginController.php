<?php
    namespace Controllers;

    use Symfony\Component\HttpFoundation;



    class LoginController {
        public function login(HttpFoundation\Request $request): HttpFoundation\Response {
            return new HttpFoundation\Response('Login page');
        }
    }
?>