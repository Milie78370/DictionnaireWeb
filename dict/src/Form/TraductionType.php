<?php
namespace App\Form;

use App\Entity\Word;
use App\Entity\GroupWord;
use App\Entity\Language;
use App\Entity\Traduction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Repository\GroupWordRepository;
use App\Repository\LanguageRepository;
use App\Repository\WordRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TraductionType  extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('translatedWord')
            ->add('def', TextareaType::class, [
                'label' => 'Définition du mot',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre mot',
                    'rows' => 5
                ]])
            ->add('wordLink', EntityType::class, [
                'class' => Word::class,
                'label' => 'Mot: ',
                'placeholder' => 'Choisir un mot',
                'required' => false,
                'choice_label' => 'inputWord',
                'query_builder' => function(WordRepository $repo) {
                    return  $repo->createQueryBuilder('w')
                        ->distinct()
                        ->orderBy('w.inputWord', 'ASC')
                        ->groupBy('w.inputWord')
                        ;
                },
                'mapped' => false
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
            'data_class' => Traduction::class,
        ]);
    }
}
