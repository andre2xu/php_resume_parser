<?php
    namespace Controllers;

    use Symfony\Component\HttpFoundation;
    use Services;



    class DashboardController extends BaseController {
        public function dashboard(HttpFoundation\Request $request): HttpFoundation\Response {
            $response = $this->generateTemplateResponse('dashboard.html');

            if ($request->cookies->has('authToken')) {
                if ($request->request->has('command')) {
                    $command = $request->request->get('command');

                    if ($command == 'logout') {
                        $response->headers->clearCookie('authToken');
                        $response->sendHeaders();

                        $response = $this->generateRedirectResponse($request, 'login');
                    }
                }

                return $response;
            }
            else {
                return $this->generateRedirectResponse($request, 'login');
            }
        }
    }
?>