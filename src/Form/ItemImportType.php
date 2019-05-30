<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ItemImportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('field_name')
            ->add('importLink', TextType::class, [
                'label' => 'Product link',
                'required' => false
            ])
            ->add('importSource', ChoiceType::class, [
                'choices' => [
                    'AliExpress' => 1,
                    'Amazon' => 2
                ],
                'data' => 'AliExpress',
                'label' => 'Select vendor'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Add product'
            ])
            ->setMethod('POST')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
