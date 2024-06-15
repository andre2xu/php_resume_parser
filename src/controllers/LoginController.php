<?php
    namespace Controllers;

    use Symfony\Component\HttpFoundation;
    use Services;



    class LoginController extends BaseController {
        public function login(HttpFoundation\Request $request): HttpFoundation\Response {
            $response = $this->generateTemplateResponse('login.html');

            if ($request->isMethod('POST')) {
                $user_email = $request->request->get('email');
                $user_password = $request->request->get('password');

                // change default response
                $response = $this->generateTemplateResponse('login.html', array('message' => 'Invalid credentials'));

                // check if an account with the given email exists
                $db = Services\db();

                $sql_statement = $db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
                $sql_statement->execute([$user_email]);

                $result = $sql_statement->fetch();

                if ($result) {
                    // account exists so check if the given password is correct
                    $user_password = hash('sha256', $user_password);

                    if ($result['password'] == $user_password) {
                        $response = $this->generateRedirectResponse($request, 'dashboard');

                        $auth_cookie = new HttpFoundation\Cookie(
                            'authToken',
                            $user_email,
                            time() + 86400,
                            '/',
                            null,
                            true, # secure
                            true # HTTP only
                        );

                        $response->headers->setCookie($auth_cookie);
                    }
                }
            }

            return $response;
        }

        public function signup(HttpFoundation\Request $request): HttpFoundation\Response {
            $response = $this->generateTemplateResponse('signup.html');

            if ($request->isMethod('POST')) {
                $user_email = $request->request->get('email');
                $user_password = $request->request->get('password');

                // check if an account with the given email already exists
                $db = Services\db();

                $sql_statement = $db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
                $sql_statement->execute([$user_email]);

                $email_exists = $sql_statement->fetch();

                if ($email_exists == false) {
                    $user_password = hash('sha256', $user_password);

                    // create an account
                    $db->beginTransaction();

                    $sql_statement = $db->prepare('INSERT INTO users (email, password) VALUES (?, ?)');

                    $sql_statement->execute([$user_email, $user_password]);

                    $db->commit();

                    // change response
                    $response = $this->generateRedirectResponse($request, 'dashboard');

                    $auth_cookie = new HttpFoundation\Cookie(
                        'authToken',
                        $user_email,
                        time() + 86400,
                        '/',
                        null,
                        true, # secure
                        true # HTTP only
                    );

                    $response->headers->setCookie($auth_cookie);
                }
                else {
                    $response = $this->generateTemplateResponse('signup.html', array(
                        'message' => 'That email already exists'
                    ));
                }
            }

            return $response;
        }
    }
?>