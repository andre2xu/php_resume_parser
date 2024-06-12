<?php
    namespace Controllers;

    use Symfony\Component\HttpFoundation;



    class LoginController extends BaseController {
        public function login(): HttpFoundation\Response {
            return $this->generateTemplateResponse('login.html');
        }

        public function signup(): HttpFoundation\Response {
            return $this->generateTemplateResponse('signup.html');
        }
    }
?>