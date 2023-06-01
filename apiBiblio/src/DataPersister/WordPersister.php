<?php
namespace App\DataPersister;

use App\Entity\GroupWord;
use App\Entity\Language;
use App\Entity\Word;
use App\Entity\User;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Doctrine\ORM\EntityManagerInterface;

class WordPersister implements ContextAwareDataPersisterInterface{
    protected $em;

    public function __construct(EntityManagerInterface $entiteManager){
        $this->em = $entiteManager;
    }
    public function supports($data, array $context = []): Bool
    {
        return $data instanceof Word;
    }
    public function persist($data, array $context = []){
        $user = new User();
        $user->setEmail('rerezZZZzss.com');  
        $user->setPassword('testMDP'); 
        $this->em->persist($user);
        $data->setUser($user);

         
        $groupWord = new GroupWord();
        $groupWord->setLabel('CATEGORieTEST'); 
        $this->em->persist($groupWord);
        $data->setGroupWord($groupWord);

        $language = new Language();
        $language->setName('Francais'); 
        $this->em->persist($language);
        $data->addLanguage($language);
        $this->em->persist($data);
        $this->em->flush();

    }
    public function remove($data, array $context = []){
        $this->em->remove($data);
        $this->em->flush();
    }
}
