<?php

namespace App\Tests\Mcp\Resource;

use App\Entity\Book;
use App\Mcp\Resource\BooksCatalogResource;
use App\Repository\BookRepository;
use PHPUnit\Framework\TestCase;

class BooksCatalogResourceTest extends TestCase
{
    public function testItReturnsTheBookCatalogAsStructuredData(): void
    {
        $book = (new Book())
            ->setTitle('Dune')
            ->setAuthor('Frank Herbert');

        $repository = $this->createMock(BookRepository::class);
        $repository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn([$book]);

        $resource = new BooksCatalogResource($repository);

        self::assertSame([
            [
                'id' => null,
                'title' => 'Dune',
                'author' => 'Frank Herbert',
            ],
        ], $resource->getCatalog());
    }
}
