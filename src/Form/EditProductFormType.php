<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          ->add('title', TextType::class, [
            'constraints' => [
              new NotBlank([], 'Should be filled') // проверка на заполненность
            ]
          ])
          ->add('price', NumberType::class, [
            'scale' => 2, // добавим две цифры после запятой
            'attr' => [
              'step' => '0.01' // добавим шаг
            ]
          ])
          ->add('quantity')
          ->add('description')
          ->add('isPublished')
          ->add('isDeleted')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
