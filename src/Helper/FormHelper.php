<?php
declare(strict_types=1);

namespace App\Helper;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class FormHelper
{
    public static function prepareFormErrorsForSerialization(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if ($child instanceof FormInterface) {
                $childErrors = self::prepareFormErrorsForSerialization($child);

                var_dump($child->getName(), (string)$child->getErrors(true, false));
                if ($childErrors) {
                    $errors[$child->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}
