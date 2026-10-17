<?php

namespace App\Mcp\Tool;

use App\Entity\Book;
use App\Repository\BookRepository;
use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;

class SearchBooksTool
{
    public function __construct(
        private BookRepository $bookRepository,
    ) {
    }

    #[McpTool(name: 'search-books', description: 'Search books by title or author.')]
    public function searchBooks(
        #[Schema(description: 'Text to find in a book title or author name.')]
        string $query,
    ): array {
        return array_map(
            static fn (Book $book): array => [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
            ],
            $this->bookRepository->searchByTitleOrAuthor($query),
        );
    }
}
