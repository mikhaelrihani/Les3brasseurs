<?php
namespace App\Controller\Web\Media;

use App\Form\ExcelType;
use App\Service\MailerService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

// l utilisateur clic sur le menu "inventaire" et peut choisir entre nouveau, actuel, archive

// si archive route twig pour afficher une modal, cette modal s ouvre avec un widget selection mois annee actuelle
// et il y aura sur la vue de l inventaire un bouton telecharger et l inventaire sera de suite editable avec un bouton sauvegarder 

// si nouveau ou actuel alors on est dirige vers une route api qui va recuperer le mois passe et l annee actuelle
// puis rediriger vers la route api "check if inventory exist"
// cette route va d abord creer un nom de fichier avec les dates et annees selectionnees
// cette route interroge la bdd pour savoir si l inventaire existe deja sur external storage 
// cette route nous retourne un boolean
// si true js fetch la route api  qui download le fichier excel depuis external storage vers public storage
// si false la modal s'agrandit avec un bouton "select excel file" qui va ouvrir l explorateur de fichier pour selectionner un fichier excel
// puis au clic sur le bouton js fetch la route pour l'upload d'un fichier excel depuis local storage vers public storage
// ensuite dans les 2 cas js fetch la route twig edit avec en parametre la variable "exist" et le path du fichier excel
// cette route va utiliser le service spreadsheet pour lire le fichier excel et renvoyer les datas a la vue twig edit
// l utilisateur peut editer les datas de l inventaire sur cette vue twig
// cette vue twig a un bouton "sauvegarder" qui va nous rediriger vers une route web "update file"avec les datas de l inventaire et les variables "exist" et "filename"
// cette route web utilise le service spreadsheet pour mettre a jour le fichier excel avec les datas modifiees
// cette route renvoie un boolean avec les variables "exist" et "filename"
// si false alors on affiche un message d erreur sur la vue twig edit et affiche le fichier excel a editer avec les datas modifiees et les erreurs
// si true alors js check si exist=false ou exist=true
// si exist=false js fetch la route api qui upload le fichier excel depuis public storage vers external storage
// si exist=true js fetch la route api qui update le fichier excel depuis public storage vers external storage
// cette route api update efface le fichier existant pour eviter les doublons
// cette route api update renvoi un boolean true si tout s est bien passe
// si true js fetch la route api delete qui efface le fichier excel depuis public storage


#[Route("/web/excel")]
class ExcelController extends AbstractController
{
    private MailerService $mailerService;
    private $uploadDirectory;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
        $this->uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/upload';
    }

    #[Route("/send-email", name: "send_email")]
    public function sendEmail(): Response
    {
        $this->mailerService->sendEmail("mikabernikdev@gmail.com", "subject", "body");
        return new Response('Email sent');
    }

    #[Route("/upload", name: "app_web_excel_upload")]
    public function upload(Request $request, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ExcelType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $excelFile = $form->get('excelFile')->getData();

            if ($excelFile) {
                $originalFileName = pathinfo($excelFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFileName);
                $fileName = $safeFilename . "." . $excelFile->guessExtension();

                try {
                    $excelFile->move(
                        $this->uploadDirectory,
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response("Error uploading file: " . $e->getMessage());
                }
                return $this->redirectToRoute('app_web_excel_edit', ['filename' => $fileName]);
            }
        }

        return $this->render('excel/upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/edit/{fileName}", name: "app_web_excel_edit")]
    public function editExcel($fileName): Response
    {
        // Logic to read the Excel file and pass the data to the view

        // Use PhpSpreadsheet to load the file
        $spreadsheet = IOFactory::load($this->uploadDirectory . '/' . $fileName);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        return $this->render('excel/edit.html.twig', [
            'data'     => $data,
            'filename' => $fileName,
        ]);
    }

    // #[Route("/select", name: "select_file")]
    // public function selectFile(Request $request): Response
    // {
    //     $finder = new Finder();
    //     $finder->files()->in($this->getParameter('kernel.project_dir') . '/public/upload');
    //     $fileChoices = [];
    //     foreach ($finder as $file) {
    //         $fileChoices[$file->getFilename()] = $file->getFilename();
    //     }

    //     $form = $this->createForm(SelectFileType::class, null, [
    //         'file_choices' => $fileChoices,
    //     ]);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $fileName = $form->get('fileName')->getData();
    //         return $this->redirectToRoute('view_file', ['fileName' => $fileName]);
    //     }

    //     return $this->render('select_file.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }


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

    #[Route("/excel/{fileName}", name: "update_excel", methods: ["POST"])]
    public function saveExcel(Request $request, $fileName): Response
    {
        $filePath = $this->uploadDirectory . $fileName;

        $modifiedData = json_decode($request->getContent(), true);

        // Désactiver le calcul automatique des formules
        \PhpOffice\PhpSpreadsheet\Calculation\Calculation::getInstance()->setCalculationCacheEnabled(false);

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Update the Excel file with the modified data
            foreach ($modifiedData as $rowIndex => $row) {
                foreach ($row as $colIndex => $cellValue) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                    $cellCoordinate = $columnLetter . ($rowIndex + 1);

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

            return new Response('File saved successfully.');
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            error_log("Error processing the spreadsheet: " . $e->getMessage());
            return new Response("Error processing the spreadsheet: " . $e->getMessage(), 500);
        }
    }
}
