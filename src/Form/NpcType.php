<?php

namespace App\Form;

use App\Entity\Npc;
use App\Entity\Story;
use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NpcType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $story = $options["story"];
        $skills = $options["skills"];
        
        $builder->add("name",
                    TextType::class, [
                        "label" => "name of the character"
                    ])
                ->add("life",
                    IntegerType::class, [
                        "label" => "HP of the character",
                        "scale" => 0
                    ])
                ->add("ability",
                    IntegerType::class, [
                        "label" => "Ability of the character",
                        "scale" => 0
                    ])
                ->add("skillAffect",
                    EntityType::class, [
                        "label" => "Weakness",
                        "class" => Skill::class,
                        "choices" => $skills,
                        "choice_label" => "name",
                        "placeholder" => "Choose one",
                        "expanded" => false,
                        "multiple" => false,
                        "required" => false,
                        "empty_data" => null
                    ])
                ->add("description",
                    TextareaType::class, [
                        "label" => "Description of the character or monster",
                        "attr" => [
                            "placeholder" => "Write a short physical of the character or monster"
                        ]
                    ])
             ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Npc::class,
            "story" => null,
            "skills" => null
        ]);
    }
}
