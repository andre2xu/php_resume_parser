<?php
    namespace Controllers;

    use Symfony\Component\HttpFoundation;
    use Services;



    class DashboardController extends BaseController {
        public function dashboard(HttpFoundation\Request $request): HttpFoundation\Response {
            if ($request->cookies->has('authToken')) {
                return $this->generateTemplateResponse('dashboard.html');
            }
            else {
                return $this->generateRedirectResponse($request, 'login');
            }
        }
    }
?>