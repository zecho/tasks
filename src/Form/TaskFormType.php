<?php

namespace App\Form;

use App\Entity\Task;
use App\Enum\ProjectStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class TaskFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description', TextareaType::class)
            ->add('status', EnumType::class, [
                'class' => ProjectStatus::class,
            ])
            ->add('start', DateType::class, [
                'input' => 'datetime_immutable',
                'constraints' => [
                    new NotNull(),
                ]
            ])
            ->add('end', DateType::class, [
                'input' => 'datetime_immutable',
                'constraints' => [
                    new NotNull(),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
