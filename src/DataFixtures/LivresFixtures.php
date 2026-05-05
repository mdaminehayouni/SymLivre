<?php

namespace App\DataFixtures;
use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory ;

class LivresFixtures extends Fixture{ 
    public function load(ObjectManager $manager): void {  
            $faker = Factory::create('fr_FR'); 
            for ($j = 1; $j <= 3; $j++) { 
                $cat = new Category(); 
                $cat->setLibelle($faker->name); 
                $cat->setDescription($faker->text) ; 
                $manager->persist($cat); 
            for ($i = 1; $i <= 15; $i++) { 
                $book = new Book(); 
                $book->setTitre($faker->name); 
                $book->setImage('https://picsum.photos/200'); 
                $book->setResume($faker->text); 
                $book->setPrix($faker->numberBetween(10, 200)); 
                $book->setEditeur($faker->company); 
                $book->setDate(new \DateTime($faker->date())); 
                $book->setCategory($cat); 
                $manager->persist($book); 
            } 
        } 
    $manager->flush();    
    }
}
