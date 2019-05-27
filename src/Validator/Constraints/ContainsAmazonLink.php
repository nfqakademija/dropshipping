<?php


namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsAmazonLink extends Constraint
{
    public $message = 'Please, provide full Amazon link to the product.';
}