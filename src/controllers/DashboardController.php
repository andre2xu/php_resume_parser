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

                if ($request->isMethod('POST')) {
                    $resumes = $request->files->get('resume-upload');

                    $valid_extensions = ['doc', 'docx', 'pdf'];

                    foreach ($resumes as $resume) {
                        $extension = $resume->getClientOriginalExtension();

                        if (in_array($extension, $valid_extensions) == false) {
                            return $this->generateTemplateResponse('dashboard.html', array(
                                'status' => 'failed',
                                'message' => 'Only MS Word documents and PDFs are allowed'
                            ));
                        }

                        $file_path = $resume->getClientOriginalPath();
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