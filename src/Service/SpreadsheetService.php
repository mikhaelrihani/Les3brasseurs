<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SpreadsheetService
{
    /**
    * TODO: AJOUTER UN PDF TO EXCEL
    */
    public function generateExcel(): Response
    {
        // Exemple de récupération des données depuis la base de données
        $repository = $this->entityManager->getRepository(User::class);
        $users = $repository->findAll();

        // Créer un nouveau fichier Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Ajouter des en-têtes de colonnes
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Email');

        // Remplir les lignes avec les données
        $rowNumber = 2; // Commence à la deuxième ligne
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $rowNumber, $user->getId());
            $sheet->setCellValue('B' . $rowNumber, $user->getName());
            $sheet->setCellValue('C' . $rowNumber, $user->getEmail());
            $rowNumber++;
        }

        // Créer un writer pour générer le fichier Excel
        $writer = new Xlsx($spreadsheet);

        // Créer un StreamedResponse pour envoyer le fichier au navigateur
        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        // Définir les en-têtes de réponse
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="users.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

}