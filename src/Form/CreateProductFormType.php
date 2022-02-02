<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Tag;
use App\Form\DataTransformer\TagsToCollectionTransformer;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CreateProductFormType extends AbstractType
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('images', CollectionType::class, [
                'entry_type' => ImagesType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype'     => true,
            ])
            ->add('name')
            ->add('price')
            ->add('description')
            ->add('tags', CollectionType::class, [
                'entry_type' => TagType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'prototype'     => true,
                'by_reference' => false,
            ])
        ;

        $builder
            ->get('tags')
            ->addModelTransformer(new TagsToCollectionTransformer($this->manager));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
