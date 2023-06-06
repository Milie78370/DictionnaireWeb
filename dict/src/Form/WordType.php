<?php

namespace App\Form;

use App\Entity\Word;
use App\Entity\GroupWord;
use App\Entity\Language;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Repository\GroupWordRepository;
use App\Repository\LanguageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class WordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('def')
            ->add('inputWord', TextareaType::class, [
                'label' => 'Définition du mot',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre mot',
                    'rows' => 5
                ],
            ])
            ->add('wordType')
            ->add('language', EntityType::class, [
                'class' => Language::class,
                'label' => 'Language: ',
                'placeholder' => 'Choisir une Language',
                'required' => false,
                'query_builder' => function(LanguageRepository $repo) {
                    return  $repo->createQueryBuilder('w')
                        ->distinct()
                        ->orderBy('w.name', 'ASC')
                        ->groupBy('w.name')
                    ;
                },
                'mapped' => false
            ])
            ->add('groupWord', EntityType::class, [
                'class' => GroupWord::class,
                'label' => 'Categorie: ',
                'placeholder' => 'Choisir une catégorie',
                'required' => false,
                'query_builder' => function(GroupWordRepository $repo) {
                    return  $repo->createQueryBuilder('w')
                        ->distinct()
                        ->orderBy('w.label', 'ASC')
                        ->groupBy('w.label')
                    ;
                }
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Word::class,
        ]);
    }
}
