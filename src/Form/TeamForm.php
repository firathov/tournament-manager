<?php

namespace App\Form;

use App\Entity\Team;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\{Length, NotBlank};

class TeamForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Не указано название']),
                    new Length([
                        'maxMessage' => 'Название не должно быть длинее 100 символов',
                        'max' => 100,
                        'minMessage' => 'Название не должно быть менее 3 символов',
                        'min' => 3,
                    ]),
                ],
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
