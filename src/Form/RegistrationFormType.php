<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [ //добавим тип
                'label' => 'Enter your email',     //добавим label
                'required' => true,                       //сделаем обязательным
                'attr' => [                               //добавим атрибуты
                    'class' => 'form-control',
                    'autofocus' => 'autofocus',
                    'placeholder' => 'Please enter your email'
                ],
                'constraints' => [                     //Добавим валидацию
                    new NotBlank([
                       'message' => 'Please fill the field'
                    ]),
                    new Email([
                        'message' => 'Please enter a validate email'
                    ])
                ]
              ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'I agree to the <a href="#">privacy policy</a>',
                'required' => true,
                'label_html' => true,              // Укажем, что label содержит HTML теги
                'mapped' => false,
                'attr' => [
                    'class' => 'custom-control-input'
                ],
                'label_attr' => [                   //добавим аттрибуты для label
                   'class' => 'custom-control-label'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Please check the box',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Enter your password',
                'required' => true,
                'mapped' => false,
                'attr' => [
                  'class' => 'form-control',
                  'autocomplete' => 'new-password',
                  'placeholder' => 'Please enter your password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
