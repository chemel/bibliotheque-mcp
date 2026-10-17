<?php

namespace App\Tests\Mcp\Tool;

use App\Entity\Book;
use App\Mcp\Tool\ListBooksTool;
use App\Repository\BookRepository;
use PHPUnit\Framework\TestCase;

class ListBooksToolTest extends TestCase
{
    public function testItListsBooksAsStructuredData(): void
    {
        $book = (new Book())
            ->setTitle('Dune')
            ->setAuthor('Frank Herbert');

        $repository = $this->createMock(BookRepository::class);
        $repository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn([$book]);

        $tool = new ListBooksTool($repository);

        self::assertSame([
            [
                'id' => null,
                'title' => 'Dune',
                'author' => 'Frank Herbert',
            ],
        ], $tool->listBooks());
    }
}
