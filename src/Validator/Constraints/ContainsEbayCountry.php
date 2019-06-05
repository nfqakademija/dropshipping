<?php


namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsEbayCountry extends Constraint
{
    public $message = 'Ebay shop country should be Germany or United Kingdom.';
}