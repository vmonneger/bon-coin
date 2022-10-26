<?php

namespace App\Form;

use App\Entity\Announces;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnounceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('image_1', FileType::class)
            // ->add('image_2', FileType::class)
            // ->add('image_3', FileType::class)
            ->add('images')
            ->add('title',  TextType::class, ['label' => 'Titre de l\'annonce',])
            ->add('description')
            ->add('price', IntegerType::class, ['label' => 'Prix de l\'annonce'])
            ->add('tags')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Announces::class,
        ]);
    }
}
