<?php

namespace App\Form;

use App\Entity\GroupWord;
use App\Entity\Word;
use App\Entity\Language;
use App\Repository\GroupWordRepository;
use App\Repository\LanguageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\TextUI\XmlConfiguration\Group;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class WordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('def')
            ->add('inputWord')
            ->add('wordType')
            ->add('language', ChoiceType::class, array( 
                'choices'  => array( 
                   'Francais' => 'Francais', 
                   'Anglais' => 'Anglais', 
                ), 
                'mapped' => false
             ))
            ->add('groupWord', ChoiceType::class, array( 
                'choices'  => array( 
                   'Nom' => 'Nom', 
                   'Verbe' => 'Verbe', 
                ), 
                'mapped' => false
            ))
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Word::class,
        ]);
    }
}
