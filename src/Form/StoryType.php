<?php

namespace App\Form;

use App\Entity\Story;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("title", 
                    TextType::class)
            ->add("author", 
                    TextType::class)
            ->add("saga", 
                    TextType::class)
            ->add("save", 
                    SubmitType::class);
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Story::class
        ]);
    }
}