<?php

namespace App\Controller;

use App\Service\ValidatorErrorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
   
    protected $validatorError;

    public function __construct(
        ValidatorErrorService $validatorError
    ) {
        $this->validatorError = $validatorError;
    }
   public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    

}
