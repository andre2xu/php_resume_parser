<?php
    namespace Controllers;

    use Symfony\Component\HttpFoundation;
    use Services;



    class DashboardController extends BaseController {
        public function dashboard(): HttpFoundation\Response {
            return $this->generateTemplateResponse('dashboard.html');
        }
    }
?>