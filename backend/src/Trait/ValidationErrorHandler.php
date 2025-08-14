<?php


namespace App\Trait;


use Symfony\Component\Validator\ConstraintViolationListInterface;

trait ValidationErrorHandler
{

    //@todo DRY with AbstractApiController
    /**
     * @param ConstraintViolationListInterface $errors
     * @return array<int<0, max>, array{property: string, message: string}>
     *
     */
    protected function transformErrors(ConstraintViolationListInterface $errors): array
    {
        $result = [];
        foreach ($errors as $error) {
            /* @var $error ConstraintViolation */
            $result[] = [
                'property' => $error->getPropertyPath(),
                'message' => (string) $error->getMessage(),
            ];
        }

        return $result;
    }
}