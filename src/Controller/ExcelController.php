<?php
namespace App\Controller;

use App\Form\UploadFormType;
use App\Form\SelectFileType;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Finder\Finder;

#[Route("excel")]
class ExcelController extends AbstractController
{

    #[Route("/upload", name: "upload_excel")]
    public function upload(Request $request): Response
    {
        $form = $this->createForm(UploadFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('excelFile')->getData();

            if ($file) {
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $originalFileName . "." . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('kernel.project_dir') . '/public/upload',
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response("Error uploading file: " . $e->getMessage());
                }

                $filePath = $this->getParameter('kernel.project_dir') . '/public/upload/' . $fileName;
                $spreadsheet = IOFactory::load($filePath);
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();

                return $this->render('excel_view.html.twig', [
                    'data'     => $data,
                    'fileName' => $fileName,
                ]);
            }
        }

        return $this->render('upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route("/select", name: "select_file")]
    public function selectFile(Request $request): Response
    {
        $finder = new Finder();
        $finder->files()->in($this->getParameter('kernel.project_dir') . '/public/upload');
        $fileChoices = [];
        foreach ($finder as $file) {
            $fileChoices[$file->getFilename()] = $file->getFilename();
        }

        $form = $this->createForm(SelectFileType::class, null, [
            'file_choices' => $fileChoices,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileName = $form->get('fileName')->getData();
            return $this->redirectToRoute('view_file', ['fileName' => $fileName]);
        }

        return $this->render('select_file.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route("/view/{fileName}", name: "view_file")]
    public function viewFile(string $fileName): Response
    {
        $filePath = $this->getParameter('kernel.project_dir') . '/public/upload/' . $fileName;
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        return $this->render('excel_view.html.twig', [
            'data'     => $data,
            'fileName' => $fileName,
        ]);
    }

    #[Route("/modify/{fileName}", name: "modify_excel")]
    public function modifyExcel(string $fileName): Response
    {
        $filePath = $this->getParameter('kernel.project_dir') . '/public/upload/' . $fileName;

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Modified Value');

        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'modified_' . $fileName
        );

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    #[Route("/update", name: "update_excel", methods: ["POST"])]
    public function updateExcel(Request $request): Response
    {
        $data = json_decode($request->request->get('data'), true);
        $fileName = $request->request->get('fileName');
        $filePath = $this->getParameter('kernel.project_dir') . '/public/upload/' . $fileName;

        // Désactiver le calcul automatique des formules
        \PhpOffice\PhpSpreadsheet\Calculation\Calculation::getInstance()->setCalculationCacheEnabled(false);

       // Log the received data for debugging
    error_log("Received data: " . print_r($data, true));
    error_log("File name: " . $fileName);

    try {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Apply the received data to the spreadsheet
        foreach ($data as $rowIndex => $row) {
            foreach ($row as $colIndex => $cellValue) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                $cellCoordinate = $columnLetter . ($rowIndex + 1);
                
                // Log the cell values for debugging
                error_log("Setting cell $cellCoordinate with value: $cellValue");

                if (is_string($cellValue) && strpos($cellValue, '=') === 0) {
                    $sheet->setCellValue($cellCoordinate, $cellValue);
                } else {
                    $sheet->setCellValueExplicit($cellCoordinate, $cellValue, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                }
            }
        }

        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'modified_' . $fileName
        );

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
        error_log("Error processing the spreadsheet: " . $e->getMessage());
        return new Response("Error processing the spreadsheet: " . $e->getMessage(), 500);
    }
    }
}
