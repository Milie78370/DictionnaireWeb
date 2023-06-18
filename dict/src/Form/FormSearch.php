<?php

namespace App\Form;

use App\Entity\GroupWord;
use App\Entity\Word;
use App\Entity\Language;
use App\Repository\GroupWordRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\LanguageRepository;

class FormSearch extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('inputWord', TextType::class, [
                'label' => 'Barre de recherche: ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher un mot'
                ]
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
            ->add('language', EntityType::class, [
                'class' => Language::class,
                'label' => 'Language: ',
                'placeholder' => 'Choisir une Language',
                'required' => false,
                'choice_label' => 'name',
                'query_builder' => function(LanguageRepository $repo) {
                    return  $repo->createQueryBuilder('w')
                        ->distinct()
                        ->orderBy('w.name', 'ASC')
                        ->groupBy('w.name')
                        ;
                },
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Word::class
        ]);
    }
}
