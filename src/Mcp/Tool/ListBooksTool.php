<?php

namespace App\Mcp\Tool;

use App\Entity\Book;
use App\Repository\BookRepository;
use Mcp\Capability\Attribute\McpTool;

class ListBooksTool
{
    public function __construct(
        private BookRepository $bookRepository,
    ) {
    }

    #[McpTool(name: 'list-books', description: 'List all books with their IDs, titles, and authors.')]
    public function listBooks(): array
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
