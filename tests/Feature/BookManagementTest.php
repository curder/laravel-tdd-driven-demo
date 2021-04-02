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
    public function a_book_can_be_add()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/books', Book::factory()->raw());

        $book = Book::first();

        $this->assertCount(1, Book::all());

        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_title_is_required()
    {
        $this->post('/books', Book::factory()->raw(['title' => '']))
             ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_author_id_is_required()
    {
        $this->post('/books', Book::factory()->raw(['author_id' => '']))
             ->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $this->post('/books', Book::factory()->raw(['author_id' => 'Author']));

        $book = Book::first();

        $response = $this->patch($book->path(), $data = Book::factory()->raw(['author_id' => 'New Author']));
        $response->assertRedirect($book->fresh()->path());

        $this->assertEquals($data['title'], $book->fresh()->title);
        $this->assertEquals(2, $book->fresh()->author_id);
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', Book::factory()->raw());

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $response->assertRedirect('/books');

        $this->assertCount(0, Book::all());
    }

    /** @test */
    public function a_new_author_is_automatically_added()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', Book::factory()->raw(['author_id' => 'New Author']));

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());

    }
}
