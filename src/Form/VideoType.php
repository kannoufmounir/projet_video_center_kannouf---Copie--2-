<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', null, [
            'attr' => [
                'class' => 'form-control h4 form-control-lg mb-3 p-3 rounded-0 text-dark', // Ajoutez 'text-light' ici
            ],
            'label' => 'Title'
        ])
        ->add('videoLink', null, [
            'attr' => [
                'class' => 'form-control h4 form-control-lg mb-3 p-3 rounded-0 text-dark', // Ajoutez 'text-light' ici
            ],
            'label' => 'Video Link'
        ])
        ->add('descripation', null, [
            'attr' => [
                'class' => 'form-control h4 form-control-lg  rows="5" p-5 rounded-0 text-dark', // Ajoutez 'text-light' ici
            ],
            'label' => 'Description'
        ])
        
        ->add('PremiumVideo', CheckboxType::class, [
            'label' => 'PremiumVideo',
            'required' => false,
            'attr' => [
                'class' => 'form-check-input', // Classe CSS pour styliser la case à cocher
            ],
        ]);
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
