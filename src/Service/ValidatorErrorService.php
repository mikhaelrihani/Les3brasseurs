<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorErrorService
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate entity or return errors messages
     *
     */
    public function returnErrors($entity)
    {

        $errors = $this->validator->validate($entity);

        if (count($errors) > 0) {

            $errorsList = [];

            foreach ($errors as $error) {
                $errorsList[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $errorsList;
        }
    }
}