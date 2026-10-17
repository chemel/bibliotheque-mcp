<?php

namespace App\Mcp\Resource;

use App\Entity\Book;
use App\Repository\BookRepository;
use Mcp\Capability\Attribute\McpResource;

class BooksCatalogResource
{
    public function __construct(
        private BookRepository $bookRepository,
    ) {
    }

    #[McpResource(
        uri: 'books://catalog',
        name: 'book-catalog',
        title: 'Book catalogue',
        description: 'The catalogue of books, including each title and author.',
        mimeType: 'application/json',
    )]
    public function getCatalog(): array
    {
        return array_map(
            static fn (Book $book): array => [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
            ],
            $this->bookRepository->findAll(),
        );
    }
}
