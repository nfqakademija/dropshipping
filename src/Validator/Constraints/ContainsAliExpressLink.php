<?php


namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsAliExpressLink extends Constraint
{
    public $message = 'Please, provide full AliExpress link to the product.';
}