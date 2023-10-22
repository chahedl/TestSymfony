<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
        $builder
        ->add('firstName')
        ->add('lastName')
        ->add('Birthday')
        ->add('Entreprise', EntityType::class, [ 
            'class' => 'App\Entity\Entreprise', // Specify the target entity class
            'choice_label' => 'name', // Customize how the choices are displayed
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
