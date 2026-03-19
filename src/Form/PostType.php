<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('title', TextType::class, [
        'constraints' => [
            new Assert\NotBlank(['message' => 'Le titre est obligatoire']),
            new Assert\Length([
                'min' => 5,
                'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères',
            ]),
        ],
    ])
    ->add('content', TextareaType::class, [
        'constraints' => [
            new Assert\NotBlank(['message' => 'Le contenu est obligatoire']),
        ],
    ])
    /* ->add('published', CheckboxType::class, [
        'required' => false,
        'label' => 'Publier',
    ]) */
    ->add('category', EntityType::class, [
        'class' => Category::class,
        'choice_label' => 'name',
        'placeholder' => 'Choisir une catégorie',
        'constraints' => [
            new Assert\NotNull(['message' => 'Veuillez choisir une catégorie']),
        ],
    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
