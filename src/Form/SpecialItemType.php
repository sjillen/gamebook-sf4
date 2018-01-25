<?php

namespace App\Form;

use App\Entity\SpecialItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SpecialItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name",
                TextType::class, [
                    "label" => "Name of the item"
                ])
            ->add("description",
                TextareaType::class, [
                    "label" => "description of the item"
                ])
            ->add('slot',
                ChoiceType::class, [
                    "label" => "Slot where the item is carried by the hero (The hero can only carry one item per slot)",
                    "choices" => [
                        "Head" => "head",
                        "Chest" => "chest",
                        "Hands" => "hands",
                        "Legs" => "legs",
                        "Feet" => "feet",
                        "Other" => null
                    ],
                    "placeholder" => "Choose a slot",
                    "multiple" => false,
                    "expanded" => false,
                    "required" => false,
                    "empty_data" => null
                ])
            ->add("starter",
                CheckboxType::class, [
                    "label" => "Is your item part of the starter inventory ?",
                    "required" => false
                ])
            ->add("attributeTargeted",
                ChoiceType::class, [
                    "label" => "Bonus Attribute",
                    "placeholder" => "Select Attribute",
                    "choices" => [
                        "Life" => "life",
                        "Ability" => "ability",
                        "None" => null
                    ],
                    "required" => false,
                    "expanded" => false,
                    "multiple" => false,
                    "empty_data" => null
                ])
            ->add("bonusGiven",
                IntegerType::class, [
                    "scale" => 0,
                    "empty_data" => "0"
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => SpecialItem::class
        ]);
    }
}
