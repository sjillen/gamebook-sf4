<?php

namespace App\Form;

use App\Entity\Choice;
use App\Entity\Chapter;
use App\Form\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChapterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("title",
                    TextType::class, [
                        "label" => "title of the chapter"
                    ])
                ->add("textContent",
                    TextareaType::class, [
                        "label" => "Content",
                        "attr" => [
                            "placeholder" => "Write the content of your chapter here..."
                        ]
                    ])
                ->add("choices",
                    CollectionType::class, [
                        "entry_type" => ChoiceType::class,
                        "entry_options" => [
                            "attr" => ["class" => "choice-box"]
                        ],
                        "required" => false
                    ])
                ->add("save",
                    SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Chapter::class
        ]);
    }
}