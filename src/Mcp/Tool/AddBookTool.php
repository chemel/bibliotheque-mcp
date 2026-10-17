<?php

namespace App\Mcp\Tool;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;

class AddBookTool
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[McpTool(name: 'add-book', description: 'Add a book with its title and author.')]
    public function addBook(
        #[Schema(description: 'Title of the book.')]
        string $title,
        #[Schema(description: 'Author of the book.')]
        string $author,
    ): array {
        $title = trim($title);
        $author = trim($author);

        if ('' === $title || '' === $author) {
            throw new \InvalidArgumentException('Book title and author must not be empty.');
        }

        $book = (new Book())
            ->setTitle($title)
            ->setAuthor($author);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
        ];
    }
}
