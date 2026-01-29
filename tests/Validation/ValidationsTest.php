<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of ValidationsTest
 *
 * @author SlameX
 */
class ValidationsTest extends KernelTestCase{
    
public function testValidationDateFormation()
{
    $formation = new Formation();
    $formation->setPublishedAt(new \DateTime('2020-11-03'));

    self::bootKernel();
    $validator = self::getContainer()->get(ValidatorInterface::class);

    $errors = $validator->validate($formation);

    $this->assertCount(0, $errors, 'La date passée doit être valide');
}
    public function testValidationDateFormationFuture()
{
    $formation = new Formation();
    $formation->setPublishedAt(new \DateTime('+1 day'));

    self::bootKernel();
    $validator = self::getContainer()->get(ValidatorInterface::class);

    $errors = $validator->validate($formation);

    $this->assertCount(1, $errors, 'Une date future doit être refusée');
}
}
