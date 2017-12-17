<?php

namespace App\Form;

use App\Entity\Skill;
use App\Entity\Weapon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $story = $options["story"];

        $builder->add("name",
                    TextType::class, [
                        "label" => "name of the skill"
                    ])
                ->add("description",
                    TextareaType::class, [
                        "label" => "description of the skill"
                    ])
                ->add("save", 
                    SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Skill::class,
            "story" => null
        ]);
    }
}
