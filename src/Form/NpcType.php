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
        
        $builder->add("name",
                    TextType::class, [
                        "label" => "name of the character"
                    ])
                ->add("life",
                    IntegerType::class, [
                        "label" => "HP of the character",
                        "scale" => 0
                    ])
                ->add("energy",
                    IntegerType::class, [
                        "label" => "Energy of the character",
                        "scale" => 0
                    ])
                ->add("skillAffect",
                    EntityType::class, [
                        "label" => "Skill effective against this character",
                        "class" => Skill::class,
                        "choices" => $story->getSkills(),
                        "choice_label" => "name",
                        "expanded" => false,
                        "multiple" => false,
                        "required" => false,
                        "empty_data" => "None"
                    ])
                ->add("description",
                    TextareaType::class, [
                        "label" => "Description of the character or monster",
                        "attr" => [
                            "placeholder" => "Write a short physical of the character or monster"
                        ]
                    ])
                ->add("submit", 
                    SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Npc::class,
            "story" => null
        ]);
    }
}
