<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', null, [
                'label' => 'What do you want to say ?',
                'attr' => [
                    'placeholder' => 'Instafrep c\'est cool',
                ]
            ])
            ->add('public')
            ->add('attachment', FileType::class)
            ->add('publishedAt', DateType::class, [
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Publish now !'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
