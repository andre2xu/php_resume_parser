<?php
    namespace Controllers;

    use Symfony\Component\HttpFoundation;



    class LoginController extends BaseController {
        public function login(): HttpFoundation\Response {
            return $this->generateTemplateResponse('login.html');
        }
    }
?>