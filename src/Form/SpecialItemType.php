<?php

namespace App\Form;

use App\Entity\SpecialItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SpecialItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slot',
                TextType::class, [
                    "label" => "Slot where the item is carried by the character"
                ])
            ->add("name",
                TextType::class, [
                    "label" => "Name of the item"
                ])
            ->add("description",
                TextareaType::class, [
                    "label" => "description of the item"
                ])
            ->add("starter",
                CheckboxType::class, [
                    "label" => "Is your item part of th starter inventory ?",
                    "required" => false
                ])
            ->add("save", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => SpecialItem::class
        ]);
    }
}
