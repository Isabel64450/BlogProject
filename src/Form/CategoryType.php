<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('color', ChoiceType::class, [
        'choices' => [
        '🔵 Bleu' => '#007bff',
        '🟢 Vert' => '#28a745',
        '🔴 Rouge' => '#dc3545',
        '🟡 Jaune' => '#ffc107',
        '⚫ Noir' => '#343a40',
        '🟣 Violet' => '#6f42c1',
    ],
])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
