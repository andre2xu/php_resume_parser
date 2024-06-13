<?php
    namespace Controllers;

    use Symfony\Component\HttpFoundation;



    class LoginController extends BaseController {
        public function login(): HttpFoundation\Response {
            return $this->generateTemplateResponse('login.html');
        }

        public function signup(HttpFoundation\Request $request): HttpFoundation\Response {
            $response = $this->generateTemplateResponse('signup.html');

            if ($request->isMethod('POST')) {
                $user_email = $request->request->get('email');
                $user_password = $request->request->get('password');

                // check if an account with the given username already exists
            }

            return $response;
        }
    }
?>