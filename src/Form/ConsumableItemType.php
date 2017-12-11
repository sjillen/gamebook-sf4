<?php

namespace App\Form;

use App\Entity\ConsumableItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                    "label" => "Alteration"
                ])
            ->add("attributeTargeted",
                TextType::class, [
                    "label" => "Attribute altered"
                ])
            ->add("save", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConsumableItem::class,
        ]);
    }
}
