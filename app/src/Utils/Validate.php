<?php

namespace App\Utils;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validate
{
    public static function validateEntity($entity,ValidatorInterface $validator)
    {
        $errors = $validator->validate($entity);

        if (count($errors) > 0) {
            $validationErrors = [];

            foreach ($errors as $error) {
                $validationErrors[$error->getPropertyPath()] = $error->getMessage();
            }
            return [
                'error' => $validationErrors,
            ];
        }
        return null;
    }

}