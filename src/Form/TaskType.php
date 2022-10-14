<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, ?array $options)
    {
        $builder
            ->add('title')
            ->add('content', TextareaType::class)
            ->add('author', HiddenType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
                'mapped' => false,
                'required' => false,
                'data' => true
//                'data' => $options
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
