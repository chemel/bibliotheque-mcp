<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @return Book[]
     */
    public function searchByTitleOrAuthor(string $query): array
    {
        $query = trim($query);

        if ('' === $query) {
            return [];
        }

        $escapedQuery = strtr($query, ['!' => '!!', '%' => '!%', '_' => '!_']);
        $pattern = '%'.$escapedQuery.'%';

        return $this->createQueryBuilder('book')
            ->andWhere("(LOWER(book.title) LIKE LOWER(:query) ESCAPE '!' OR LOWER(book.author) LIKE LOWER(:query) ESCAPE '!')")
            ->setParameter('query', $pattern)
            ->orderBy('book.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
