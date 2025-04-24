<?php

namespace App\Tests\Controller;

use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SessionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $sessionRepository;
    private string $path = '/session/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->sessionRepository = $this->manager->getRepository(Session::class);

        foreach ($this->sessionRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Session index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'session[debut]' => 'Testing',
            'session[fin]' => 'Testing',
            'session[messagesEnvoyes]' => 'Testing',
            'session[messagesRecus]' => 'Testing',
            'session[operateur]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->sessionRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Session();
        $fixture->setDebut('My Title');
        $fixture->setFin('My Title');
        $fixture->setMessagesEnvoyes('My Title');
        $fixture->setMessagesRecus('My Title');
        $fixture->setOperateur('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Session');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Session();
        $fixture->setDebut('Value');
        $fixture->setFin('Value');
        $fixture->setMessagesEnvoyes('Value');
        $fixture->setMessagesRecus('Value');
        $fixture->setOperateur('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'session[debut]' => 'Something New',
            'session[fin]' => 'Something New',
            'session[messagesEnvoyes]' => 'Something New',
            'session[messagesRecus]' => 'Something New',
            'session[operateur]' => 'Something New',
        ]);

        self::assertResponseRedirects('/session/');

        $fixture = $this->sessionRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getDebut());
        self::assertSame('Something New', $fixture[0]->getFin());
        self::assertSame('Something New', $fixture[0]->getMessagesEnvoyes());
        self::assertSame('Something New', $fixture[0]->getMessagesRecus());
        self::assertSame('Something New', $fixture[0]->getOperateur());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Session();
        $fixture->setDebut('Value');
        $fixture->setFin('Value');
        $fixture->setMessagesEnvoyes('Value');
        $fixture->setMessagesRecus('Value');
        $fixture->setOperateur('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/session/');
        self::assertSame(0, $this->sessionRepository->count([]));
    }
}
