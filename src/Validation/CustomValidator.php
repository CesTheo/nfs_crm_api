<?php


namespace App\Validation;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Helper\HttpResponseHelper;

class CustomValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($data)
    {
        $violationList = $this->validator->validate($data);

        $errors = [];
        foreach ($violationList as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        if(count($errors) > 0) {
            throw new BadRequestHttpException("Un ou plusieurs champs ne sont pas valides.");
        }
    }
}