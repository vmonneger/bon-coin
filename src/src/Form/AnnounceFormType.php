<?php

namespace App\Form;

use App\Entity\Announces;
use App\Entity\Tags;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AnnounceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Image', FileType::class,array('data_class' => null))
            ->add('Image_2', FileType::class,array('data_class' => null))
            ->add('Image_3', FileType::class,array('data_class' => null))
            ->add('Image_4', FileType::class,array('data_class' => null))
            ->add('title',  TextType::class, ['label' => 'Titre de l\'annonce',])
            ->add('description')
            ->add('price', IntegerType::class, ['label' => 'Prix de l\'annonce'])
            ->add('tags', EntityType::class, [
                "multiple" => true,
                'class' => Tags::class,
                'choice_label' => 'name'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Announces::class,
        ]);
    }
}
