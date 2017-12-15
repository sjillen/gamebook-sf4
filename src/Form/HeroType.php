<?php

namespace App\Form;

use App\Entity\Hero;
use App\Entity\Skill;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $story = $options['story'];

        $builder
            ->add('name', TextType::class, [
                "label" => "Name of your hero : "
            ])
            ->add('skills', EntityType::class, [
                "class" => Skill::class,
                "choices" => $story->getSkills(),
                "choice_label" => "uniqueName",
                "expanded" => true,
                "multiple" => true,
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hero::class,
            'story' => null
        ]);
    }
}
