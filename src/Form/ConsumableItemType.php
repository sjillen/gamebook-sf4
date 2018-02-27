<?php

namespace App\Form;

use App\Entity\Choice;
use App\Entity\ConsumableItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsumableItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',
                TextType::class, [
                    "label" => "name of the consumable"
                ])
            ->add('description',
                TextareaType::class, [
                    "label" => "Description of the consumable"
                ])
            ->add("bonusGiven",
                NumberType::class, [
                    "label" => "Bonus given",
                    "required" => false,
                    "scale" => 0,
                    "empty_data" => "0"
                ])
            ->add("attributeTargeted",
                ChoiceType::class, [
                    "label" => "Attribute altered",
                    "placeholder" => "Select one...",
                    "required" => false,
                    "choices" => [
                        "Life" => "life",
                        "Ability" => "ability",
                        "None" => ""
                    ],
                    "expanded" => false,
                    "multiple" => false,
                    "empty_data" => ""
                ])
            ->add("removable",
                CheckboxType::class,[
                "data" => true
                ])
            ->add("starter",
                CheckboxType::class, [
                    "label" => "Is your item part of the starter inventory ?",
                    "required" => false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConsumableItem::class,
        ]);
    }
}
