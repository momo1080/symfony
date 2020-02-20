<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\Techno;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TechnoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('logo', FileType::class, [
                'label' => "ajouter un logo",
                'mapped' => false,
                'required' => false,
            ])
            ->add('projets', EntityType::class, [
                'class'=> Projet::class,
                'multiple'=>true,
                'expanded'=> true
            ])
            ->add('ajouter', SubmitType::class, [
                'label'=> 'Ajouter la techno'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Techno::class,            
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'techno_item',
        ]);
    }


   
}
