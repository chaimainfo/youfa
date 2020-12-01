<?php

namespace App\DataFixtures;

use App\Entity\TypeTab;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TypeTabFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $type1=new TypeTab();
        $type2=new TypeTab();
        $type3=new TypeTab();
        $type1->setType('vendeur');
        $type2->setType('commande');
        $type3->setType('communication');
        $manager->persist($type1);
        $manager->persist($type2);
        $manager->persist($type3);
        $manager->flush();

    }
}
