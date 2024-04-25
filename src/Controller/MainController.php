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

}
