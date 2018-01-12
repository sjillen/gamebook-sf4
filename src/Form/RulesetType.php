<?php

namespace App\Form;

use App\Entity\Ruleset;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RulesetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('heroBaseLife',
                IntegerType::class, [
                    "label" => "Minimum Amount of life given to a hero at beginning",
                    "scale" => 0,
                    "attr" => [
                        "min" => 5
                    ]
                ])
            ->add("heroBaseResource",
                IntegerType::class, [
                    "label" => "Minimum Amount of Resources given to a hero at beginning",
                    "scale" => 0,
                    "attr" => [
                        "min" => 0
                    ]
                ])
            ->add("heroBaseGold",
                IntegerType::class, [
                    "label" => "Minimum amount of Gold given to the hero at beginning",
                    "scale" => 0,
                    "attr" => [
                        "min" => 0
                    ]
                ])
            ->add("maxSkill",
                IntegerType::class, [
                    "label" => "Number of skills the hero can choose at beginning",
                    "scale" => 0,
                    "attr" => [
                        "min" => 1
                    ]
                ])
            ->add("maxWeaponCarried",
                IntegerType::class, [
                    "label" => "Maximum number of weapons that can be carried at same time by the hero",
                    "scale" => 0,
                    "attr" => [
                        "min" => 1
                    ]
                ])
            ->add("backpackCapacity",
                IntegerType::class, [
                    "label" => "Maximum number of item that can be carried in the backpack of the hero",
                    "scale" => 0,
                    "attr" => [
                        "min" => 1
                    ]
                ])
            ->add("diceType",
                ChoiceType::class, [
                    "label" => "Type of dice you want to use for random operations",
                    "expanded" => false,
                    "multiple" => false,
                    "choices" => [
                        "D10" => 10,
                        "D12" => 12,
                        "D20" => 20
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Ruleset::class,
        ]);
    }
}
