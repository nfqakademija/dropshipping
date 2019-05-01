<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\EbayCountryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileFormType extends AbstractType
{
    private $ebayCountry;

    public function __construct(EbayCountryRepository $ebayCountry)
    {
        $this->ebayCountry = $ebayCountry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $countries = $this->ebayCountry->getCountries();
        $choices = [];
        foreach ($countries as $value) {
            $choices[$value->getName()] = $value->getId();
        }

        $builder
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'label' => 'Change password',
            ])
            ->add('firstName')
            ->add('lastName')
            ->add('ebayCountry', ChoiceType::class, [
                'choices' => $choices,
                'label' => 'Select eBay country'
            ])
            ->add('save', SubmitType::class, ['label' => 'Update profile'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
