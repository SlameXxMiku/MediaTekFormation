<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RepositoryTest extends KernelTestCase
{
    private FormationRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->repository = self::getContainer()
            ->get('doctrine')
            ->getRepository(Formation::class);
    }

    public function testFindAllFormations(): void
    {
        $formations = $this->repository->findAll();

        $this->assertIsArray($formations);
    }
}
