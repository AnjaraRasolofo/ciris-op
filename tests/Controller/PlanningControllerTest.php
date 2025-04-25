<?php

namespace App\Tests\Controller;

use App\Entity\Planning;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PlanningControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $planningRepository;
    private string $path = '/planning/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->planningRepository = $this->manager->getRepository(Planning::class);

        foreach ($this->planningRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Planning index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'planning[debut]' => 'Testing',
            'planning[fin]' => 'Testing',
            'planning[type]' => 'Testing',
            'planning[operateur]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->planningRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Planning();
        $fixture->setDebut('My Title');
        $fixture->setFin('My Title');
        $fixture->setType('My Title');
        $fixture->setOperateur('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Planning');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Planning();
        $fixture->setDebut('Value');
        $fixture->setFin('Value');
        $fixture->setType('Value');
        $fixture->setOperateur('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'planning[debut]' => 'Something New',
            'planning[fin]' => 'Something New',
            'planning[type]' => 'Something New',
            'planning[operateur]' => 'Something New',
        ]);

        self::assertResponseRedirects('/planning/');

        $fixture = $this->planningRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getDebut());
        self::assertSame('Something New', $fixture[0]->getFin());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getOperateur());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Planning();
        $fixture->setDebut('Value');
        $fixture->setFin('Value');
        $fixture->setType('Value');
        $fixture->setOperateur('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/planning/');
        self::assertSame(0, $this->planningRepository->count([]));
    }
}
