<?php

namespace App\Form;

use App\Entity\Choice;
use App\Entity\Chapter;
use App\Entity\Skill;
use App\Entity\SpecialItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $story = $options["story"];
        $skills = $options["skills"];

        $builder->add("description", 
                    TextType::class)
            ->add("targetChapter", 
                    EntityType::class, [
                        "class" => Chapter::class,                     
                        "choices" => $story->getChapters(),
                        "choice_label" => "title",
                        "expanded" => false,
                        "multiple" => false,                    
                    ])
            ->add('backpackLost',
                    CheckboxType::class, [
                        "label" => "The hero will loose all items in his backpack",
                        "required" => false
                ])
            ->add("weaponLost",
                    ChoiceType::class, [
                        "label" => "Weapons that the hero will loose",
                        "empty_data" => 0,
                        "expanded" => false,
                        "multiple" => false,
                        "choices" => [
                            "None" => 0,
                            "One Weapon" => 1,
                            "All Weapons" => 2
                        ]
                ])
            ->add("randomized",
                    CheckboxType::class, [
                        "label" => "Make this choice randomly chosen among other random choices ?",
                        "required" => false,
                ])
            ->add("locked",
                    CheckboxType::class, [
                        "label" => "Do you wish to lock this choice? ",
                        "required" => false
                    ])
            ->add("skillRequired",
                    EntityType::class, [
                        "label" => "Skill required to unlock the choice",
                        "class" => Skill::class,
                        "choices" => $skills,
                        "choice_label" => "name",
                        "expanded" => false,
                        "multiple" => false,
                        "required" => false,

                    ])
            ->add("itemRequired",
                    EntityType::class, [
                        "label" => "Item required to unlock the choice",
                        "class" => SpecialItem::class,
                        "choices" => $story->getSpecialItems(),
                        "choice_label" => "name",
                        "expanded" => false,
                        "multiple" => false,
                        "required" => false,

                    ])
            ->add("goldRequired",
                    IntegerType::class, [
                        "label" => "Amount of gold required to the unlock choice",
                        "scale" => 0,
                        "empty_data" => "0"
                    ])
            ->add("damages",
                    IntegerType::class, [
                        "label" => "Damages inflicted to the hero if he chooses this path",
                        "scale" => 0,
                        "empty_data" => "0"
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Choice::class,
            "story" => null,
            "skills" => null
        ]);
    }
}
