<?php

namespace App\Controller;

use App\Service\ValidatorErrorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{

    protected $validatorError;
    protected $baseURL = "https://ik.imagekit.io/";

    public function __construct(
        ValidatorErrorService $validatorError
    ) {
        $this->validatorError = $validatorError;
    }
    protected function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }


    protected function addPeriodFilter($queryBuilder, $dateParams)
    {
        // Vérifier si la date est une période
        if (count($dateParams) >= 2 && isset($dateParams[ 'start_year' ], $dateParams[ 'end_year' ])) {
            $startYear = $dateParams[ 'start_year' ];
            $endYear = $dateParams[ 'end_year' ];

            // Vérifier si les mois et les jours sont fournis, sinon définir les valeurs par défaut
            $startMonth = $dateParams[ 'start_month' ] ?? 1;
            $endMonth = $dateParams[ 'end_month' ] ?? 12;
            $startDay = $dateParams[ 'start_day' ] ?? 1;
            $endDay = $dateParams[ 'end_day' ] ?? 31;

            // Assurez-vous que les dates sont valides
            if (!checkdate($startMonth, $startDay, $startYear) || !checkdate($endMonth, $endDay, $endYear)) {
                return $this->json(["error" => "Date invalide"], Response::HTTP_BAD_REQUEST);
            }

            $queryBuilder->innerJoin('o.date', 'd');
            $queryBuilder->andWhere("d.year BETWEEN :start_year AND :end_year")
                ->andWhere("d.month BETWEEN :start_month AND :end_month")
                ->andWhere("d.day BETWEEN :start_day AND :end_day")
                ->setParameter('start_year', $startYear)
                ->setParameter('end_year', $endYear)
                ->setParameter('start_month', $startMonth)
                ->setParameter('end_month', $endMonth)
                ->setParameter('start_day', $startDay)
                ->setParameter('end_day', $endDay);
        } else {
            return $this->json(["error" => "Format de paramètre de date invalide"], Response::HTTP_BAD_REQUEST);
        }
        
    }
    function monthNumberToName($monthNumber) {
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
    
        return $months[$monthNumber];
    }


    protected function addDateFilter($queryBuilder, $dateParams)
    {
        if (isset($dateParams[ 'year' ])) {
            $year = $dateParams[ 'year' ];
            $month = $dateParams[ 'month' ] ?? null;
            $day = $dateParams[ 'day' ] ?? null;

            $queryBuilder->innerJoin('o.date', 'd');
            $queryBuilder->andWhere("d.year = :year")
                ->setParameter('year', $year);

            if ($month !== null) {
                $queryBuilder->andWhere("d.month = :month")
                    ->setParameter('month', $month);
            }

            if ($day !== null) {
                $queryBuilder->andWhere("d.day = :day")
                    ->setParameter('day', $day);
            }
        } else {
            return $this->json(["error" => "Invalid date parameter format"], Response::HTTP_BAD_REQUEST);
        }


    }



}
