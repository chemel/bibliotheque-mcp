<?php

namespace App\Tests\Mcp\Tool;

use App\Entity\Book;
use App\Mcp\Tool\SearchBooksTool;
use App\Repository\BookRepository;
use PHPUnit\Framework\TestCase;

class SearchBooksToolTest extends TestCase
{
    public function testItReturnsBooksMatchingTheSearch(): void
    {
        $book = (new Book())
            ->setTitle('Dune')
            ->setAuthor('Frank Herbert');

        $repository = $this->createMock(BookRepository::class);
        $repository
            ->expects(self::once())
            ->method('searchByTitleOrAuthor')
            ->with('Herbert')
            ->willReturn([$book]);

        $tool = new SearchBooksTool($repository);

        self::assertSame([
            [
                'id' => null,
                'title' => 'Dune',
                'author' => 'Frank Herbert',
            ],
        ], $tool->searchBooks('Herbert'));
    }
}
