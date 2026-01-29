<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Formation;
use App\Entity\Categorie;
use App\Entity\Playlist;
/**
 * Description of EntityTest
 *
 * @author SlameX
 */
class EntityTest extends TestCase {

    public function testgetPublishedAtString(){
        $Formation = new Formation();
        $Formation->setPublishedAt(new \DateTime("2005-11-18"));
        $this->assertEquals("18/11/2005", $Formation->getPublishedAtString());
    }
    
    public function testgetNameCategorie()
    {
        $Categorie = new Categorie();
        $Categorie->setName('Test Name');

        $this->assertSame('Test Name', $Categorie->getName());
    }
    
    public function testgetNamePlaylist(){
        $Playlist = new Playlist();
        $Playlist->setName('Test Name');

        $this->assertSame('Test Name', $Playlist->getName());
    }
}

