<?php

namespace App\Form;

use App\Entity\AmazonItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmazonItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('productId')
            ->add('titleForEbay')
            ->add('stockForEbay')
            ->add('priceForEbay')
            ->add('descriptionForEbay')
            //->add('crawled')
            //->add('created')
            ->add('categoryForEbay')
            //->add('imageUrl')
            //->add('active')
            //->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AmazonItem::class,
        ]);
    }
}
