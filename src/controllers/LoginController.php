<?php
    namespace Controllers;

    use Symfony\Component\HttpFoundation;
    use Services;



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