<?php

namespace App\Tests\Controller;

use App\Entity\Conge;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CongeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $congeRepository;
    private string $path = '/conge/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->congeRepository = $this->manager->getRepository(Conge::class);

        foreach ($this->congeRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Conge index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'conge[debut]' => 'Testing',
            'conge[fin]' => 'Testing',
            'conge[motif]' => 'Testing',
            'conge[status]' => 'Testing',
            'conge[user]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->congeRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Conge();
        $fixture->setDebut('My Title');
        $fixture->setFin('My Title');
        $fixture->setMotif('My Title');
        $fixture->setStatus('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Conge');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Conge();
        $fixture->setDebut('Value');
        $fixture->setFin('Value');
        $fixture->setMotif('Value');
        $fixture->setStatus('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'conge[debut]' => 'Something New',
            'conge[fin]' => 'Something New',
            'conge[motif]' => 'Something New',
            'conge[status]' => 'Something New',
            'conge[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/conge/');

        $fixture = $this->congeRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getDebut());
        self::assertSame('Something New', $fixture[0]->getFin());
        self::assertSame('Something New', $fixture[0]->getMotif());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Conge();
        $fixture->setDebut('Value');
        $fixture->setFin('Value');
        $fixture->setMotif('Value');
        $fixture->setStatus('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/conge/');
        self::assertSame(0, $this->congeRepository->count([]));
    }
}
