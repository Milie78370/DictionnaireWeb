<?php

namespace App\Test\Controller;

use App\Entity\Word;
use App\Repository\WordRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WordControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private WordRepository $repository;
    private string $path = '/word/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Word::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Word index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'word[def]' => 'Testing',
            'word[inputWord]' => 'Testing',
            'word[wordType]' => 'Testing',
            'word[user]' => 'Testing',
            'word[groupWord]' => 'Testing',
        ]);

        self::assertResponseRedirects('/word/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Word();
        $fixture->setDef('My Title');
        $fixture->setInputWord('My Title');
        $fixture->setWordType('My Title');
        $fixture->setUser('My Title');
        $fixture->setGroupWord('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Word');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Word();
        $fixture->setDef('My Title');
        $fixture->setInputWord('My Title');
        $fixture->setWordType('My Title');
        $fixture->setUser('My Title');
        $fixture->setGroupWord('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'word[def]' => 'Something New',
            'word[inputWord]' => 'Something New',
            'word[wordType]' => 'Something New',
            'word[user]' => 'Something New',
            'word[groupWord]' => 'Something New',
        ]);

        self::assertResponseRedirects('/word/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDef());
        self::assertSame('Something New', $fixture[0]->getInputWord());
        self::assertSame('Something New', $fixture[0]->getWordType());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getGroupWord());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Word();
        $fixture->setDef('My Title');
        $fixture->setInputWord('My Title');
        $fixture->setWordType('My Title');
        $fixture->setUser('My Title');
        $fixture->setGroupWord('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/word/');
    }
}
