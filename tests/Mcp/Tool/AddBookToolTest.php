<?php

namespace App\Tests\Mcp\Tool;

use App\Entity\Book;
use App\Mcp\Tool\AddBookTool;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class AddBookToolTest extends TestCase
{
    public function testItPersistsAndReturnsTheBook(): void
    {
        $persistedBook = null;
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects(self::once())
            ->method('persist')
            ->willReturnCallback(static function (Book $book) use (&$persistedBook): void {
                $persistedBook = $book;
            });
        $entityManager
            ->expects(self::once())
            ->method('flush');

        $tool = new AddBookTool($entityManager);

        self::assertSame([
            'id' => null,
            'title' => 'Dune',
            'author' => 'Frank Herbert',
        ], $tool->addBook(' Dune ', ' Frank Herbert '));

        self::assertInstanceOf(Book::class, $persistedBook);
        self::assertSame('Dune', $persistedBook->getTitle());
        self::assertSame('Frank Herbert', $persistedBook->getAuthor());
    }

    public function testItRejectsAnEmptyTitleOrAuthor(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects(self::never())
            ->method('persist');

        $tool = new AddBookTool($entityManager);

        $this->expectException(\InvalidArgumentException::class);
        $tool->addBook('  ', 'Frank Herbert');
    }
}
