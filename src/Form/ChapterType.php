<?php

namespace App\Form;

use App\Entity\Chapter;
use App\Entity\ConsumableItem;
use App\Entity\Npc;
use App\Entity\SpecialItem;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChapterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $story = $options["story"];

        $builder->add("title",
                    TextType::class, [
                        "label" => "title of the chapter"
                    ])
                ->add("type",
                    ChoiceType::class, [
                        "label" => "type of chapter",
                        "required" => true,
                        "expanded" => false,
                        "multiple" => false,
                        "choices" => [
                            "Standard" => "standard",
                            "Starter" => "starter",
                            "Death" => "death"
                        ],
                        "empty_data" => "standard"
                    ])
                ->add("textContent1",
                    TextareaType::class, [
                        "label" => "First part of the chapter",
                        "attr" => [
                            "placeholder" => "Write the main content of your chapter here..."
                        ]
                    ])
                ->add("npcs",
                    CollectionType::class, [
                        "entry_type" => EntityType::class,
                        "entry_options" => [
                            "attr" => ["class" => "npc-box"],
                            "class" => Npc::class,
                            "choices" => $story->getNpcs(),
                            "choice_label" => "name",
                            "expanded" => false,
                            "multiple" => false,
                        ],
                        "required" => false,
                        "by_reference" => false,
                        "label" => false,
                        "allow_add" => true,
                        "allow_delete" => true
                    ])
                ->add("specialItems",
                    EntityType::class, [
                        "class" => SpecialItem::class,
                        "label" => "Special Items you want to include in the chapter: ",
                        "choices" => $story->getSpecialItems(),
                        "choice_label" => "name",
                        "expanded" => true,
                        "multiple" => true,
                        "required" => false,
                        "empty_data" => "None"
                    ])
                ->add('consumableItems',
                    EntityType::class, [
                        "class" => ConsumableItem::class,
                        "label" => "Consumable Items you want to include in the chapter: ",
                        "choices" => $story->getConsumableItems(),
                        "choice_label" => "name",
                        "expanded" => true,
                        "multiple" => true,
                        "required" => false,
                        "empty_data" => "None"
                    ])
                ->add('textContent2',
                    TextareaType::class, [
                        "label" => "Second Part of the chapter (optionnal)",
                        "attr" => [
                            "placeholder" => "Write here the content that you want to appear after the pickable items, or after a possible fight. This part is optionnal..."
                        ],
                        "required" => false,
                    ])
                ->add("choices",
                    CollectionType::class, [
                        "entry_type" => ActionType::class,
                        "entry_options" => [
                            "attr" => ["class" => "choice-box"],
                            "story" => $story
                        ],
                        "required" => false,
                        "label" => false,
                        "by_reference" => false,
                        "allow_add" => true,
                        "allow_delete" => true
                    ])
                ->add("save",
                    SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Chapter::class,
            "story" => null,
        ]);
    }
}