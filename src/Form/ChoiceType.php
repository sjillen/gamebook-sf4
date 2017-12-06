<?php

namespace App\Form;

use App\Entity\Choice;
use App\Entity\Chapter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("description", 
                    TextType::class)
            ->add("targetChapter", 
                    EntityType::class, [
                        "class" => Chapter::class,                        
                        "choices" => $story->getChapters(),
                        "choice_label" => "title",
                        "expanded" => false,
                        "multiple" => false                      
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
                        "choices" => $story->getSkills(),
                        "choice_label" => "name",
                        "expanded" => false,
                        "multiple" => false
                    ])
            ->add("itemRequired",
                    EntityType::class, [
                        "label" => "Item required to unlock the choice",
                        "class" => Item::class,
                        "choices" => $story->getItems(),
                        "choice_label" => "name",
                        "expanded" => false,
                        "multiple" => false
                    ])
            ->add("goldRequired",
                    IntegerType::class, [
                        "label" => "Amount of gold required to the unlock choice",
                        "scale" => 0
                    ])
            ->add("save", 
                    SubmitType::class);
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Choice::class
        ]);
    }
}
