<?php
    namespace Controllers;

    use Symfony\Component\HttpFoundation;
    use Gotenberg\Gotenberg;
    use Gotenberg\Stream;
    use Gotenberg\Exceptions\GotenbergApiErrored;
    use Services;



    class DashboardController extends BaseController {
        public function dashboard(HttpFoundation\Request $request): HttpFoundation\Response {
            $response = $this->generateTemplateResponse('dashboard.html');

            if ($request->cookies->has('authToken')) {
                $user_email = $request->cookies->get('authToken');
                $db = Services\db();

                // get user account data
                $sql_statement = $db->prepare('SELECT * FROM users WHERE email = "' . $user_email . '" LIMIT 1');
                $sql_statement->execute();

                $user_account_data = $sql_statement->fetch();

                if ($request->isMethod('POST')) {
                    if ($request->request->has('command')) {
                        // process commands

                        $command = $request->request->get('command');

                        if ($command == 'logout') {
                            $response->headers->clearCookie('authToken');
                            $response->sendHeaders();

                            return $this->generateRedirectResponse($request, 'login');
                        }
                        else if ($command == 'deleteAllResumes') {
                            $sql_statement = $db->prepare('SELECT filename from pdfs WHERE user_id=?');
                            $sql_statement->execute([$user_account_data['id']]);

                            $result = $sql_statement->fetchAll();

                            if ($result) {
                                foreach ($result as $pdf_entry) {
                                    $resume_path = $this->PDF_FOLDER_PATH . $pdf_entry['filename'];

                                    if (file_exists($resume_path)) {
                                        unlink($resume_path);
                                    }
                                }

                                $db->exec('DELETE FROM pdfs');
                            }
                        }
                    }
                    else if ($user_account_data) {
                        // validate file format of resume(s)
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
                        }

                        // convert resume(s) to PDF (if not already) and save to the server's static folder
                        $file_data = $_FILES['resume-upload'];
                        $file_names = $file_data['name'];
                        $file_paths = $file_data['tmp_name'];

                        $num_of_resumes = count($file_names);

                        for ($i = 0; $i < $num_of_resumes; $i++) {
                            $file_name = $file_names[$i];
                            $extension = pathinfo($file_name)['extension'];

                            $pdf_name = hash('sha1', $user_email . $file_name . time()) . '.pdf';

                            if ($extension == 'pdf') {
                                move_uploaded_file($file_paths[$i], $this->PDF_FOLDER_PATH . $pdf_name);
                            }
                            else {
                                // save the non-PDF file temporarily to the server's temp directory
                                $new_file_path = sys_get_temp_dir() . '/' . $file_name;

                                move_uploaded_file($file_paths[$i], $new_file_path);

                                // upload the non-PDF file to Gotenberg's LibreOffice API for conversion to PDF
                                $pdf_conversion_request = Gotenberg::libreOffice('http://gotenberg:3000')->exportFormFields(false)->convert(Stream::path($new_file_path));

                                try {
                                    // save to the server's static folder
                                    $pdf_name = Gotenberg::save($pdf_conversion_request, $this->PDF_FOLDER_PATH);
                                }
                                catch (GotenbergApiErrored $error) {
                                    var_dump($error);
                                }
                            }

                            // map the PDF to the user's account
                            $db->beginTransaction();

                            $sql_statement = $db->prepare('INSERT INTO pdfs (user_id, filename, alias) VALUES (?, ?, ?)');
                            $sql_statement->execute([$user_account_data['id'], $pdf_name, $file_name]);

                            $db->commit();
                        }
                    }
                }

                // pass the user's updated list of uploaded resumes to their dashboard
                $sql_statement = $db->prepare('SELECT * FROM pdfs WHERE user_id=?');
                $sql_statement->execute([$user_account_data['id']]);

                $result = $sql_statement->fetchAll();

                $uploaded_resumes = [];

                foreach ($result as $row) {
                    array_push($uploaded_resumes, $row['alias']);
                }

                $response = $this->generateTemplateResponse('dashboard.html', array('uploaded_resumes' => $uploaded_resumes));

                return $response;
            }
            else {
                return $this->generateRedirectResponse($request, 'login');
            }
        }

        public function filter(HttpFoundation\Request $request): HttpFoundation\Response {
            $response = new HttpFoundation\JsonResponse(array());

            if ($request->cookies->has('authToken')) {
                $keywords = $request->toArray()['keywords'];

                $db = Services\db();
                $user_email = $request->cookies->get('authToken');

                // get paths to user's PDFs
                $pdf_data = [];

                $sql_statement = $db->prepare('SELECT filename, alias FROM pdfs INNER JOIN users ON pdfs.user_id=users.id WHERE users.email=?');
                $sql_statement->execute([$user_email]);

                $result = $sql_statement->fetchAll();

                if ($result) {
                    foreach ($result as $row) {
                        $file_name = $row['filename'];
                        $pdf_path = $this->PDF_FOLDER_PATH . $file_name;

                        array_push($pdf_data, array(
                            'filename' => $file_name,
                            'alias' => $row['alias'],
                            'filepath' => $pdf_path
                        ));
                    }
                }

                // get the contents of a user's PDFs
                if (count($pdf_data) > 0) {
                    $pdf_parser = new \Smalot\PdfParser\Parser();

                    $filter_results = [];

                    foreach ($pdf_data as $data) {
                        $pdf = $pdf_parser->parseFile($data['filepath']);
                        $pdf_content = $pdf->getText();

                        $matches = [];

                        // find keywords in resume
                        foreach ($keywords as $keyword) {
                            if (preg_match('/' . $keyword . '/i', $pdf_content)) {
                                array_push($matches, $keyword);
                            }
                        }

                        // keep track of resume if it has matches
                        if (count($matches) > 0) {
                            array_push($filter_results, array(
                                'resume' => $data['alias'],
                                'pdf' => $data['filename'],
                                'matches' => $matches
                            ));
                        }
                    }

                    $response = $this->generateJSONResponse(array('filterResults' => $filter_results));
                }
            }

            return $response;
        }
    }
?>