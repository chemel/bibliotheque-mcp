<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $books = [
            ['Les Misérables', 'Victor Hugo'],
            ['Le Petit Prince', 'Antoine de Saint-Exupéry'],
            ['Le Comte de Monte-Cristo', 'Alexandre Dumas'],
            ['Notre-Dame de Paris', 'Victor Hugo'],
            ['Madame Bovary', 'Gustave Flaubert'],
            ['L’Étranger', 'Albert Camus'],
            ['Germinal', 'Émile Zola'],
            ['Le Rouge et le Noir', 'Stendhal'],
            ['Candide', 'Voltaire'],
        ];

        foreach ($books as [$title, $author]) {
            $book = (new Book())
                ->setTitle($title)
                ->setAuthor($author);

            $manager->persist($book);
        }

        $manager->flush();
    }
}
