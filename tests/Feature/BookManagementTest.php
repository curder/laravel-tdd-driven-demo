<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class BookReservationTest
 *
 * @package Tests\Feature
 */
class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_add() : void
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/books', Book::factory()->raw());

        $book = Book::first();

        self::assertCount(1, Book::all());

        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_title_is_required() : void
    {
        $this->post('/books', Book::factory()->raw(['title' => '']))
             ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_author_id_is_required() : void
    {
        $this->post('/books', Book::factory()->raw(['author_id' => '']))
             ->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function a_book_can_be_updated() : void
    {
        $this->withoutExceptionHandling();
        $this->post('/books', Book::factory()->raw(['author_id' => 'Author']));

        $book = Book::first();

        $response = $this->patch($book->path(), $data = Book::factory()->raw(['author_id' => 'New Author']));
        $response->assertRedirect($book->fresh()->path());

        self::assertEquals($data['title'], $book->fresh()->title);
        self::assertEquals(2, $book->fresh()->author_id);
    }

    /** @test */
    public function a_book_can_be_deleted() : void
    {
        $this->withoutExceptionHandling();

        $this->post('/books', Book::factory()->raw());

        $book = Book::first();
        self::assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $response->assertRedirect('/books');

        self::assertCount(0, Book::all());
    }

    /** @test */
    public function a_new_author_is_automatically_added() : void
    {
        $this->withoutExceptionHandling();

        $this->post('/books', Book::factory()->raw(['author_id' => 'New Author']));

        $book = Book::first();
        $author = Author::first();

        self::assertEquals($author->id, $book->author_id);
        self::assertCount(1, Author::all());
    }
}
