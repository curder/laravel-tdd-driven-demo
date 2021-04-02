<?php

namespace Tests\Feature;

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

        $response = $this->post('/books', [
            'title' => 'Cool Book Title',
            'author' => 'Curder',
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());

        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_title_is_required()
    {
        $this->post('/books', [
            'title' => '',
            'author' => 'Curder',
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_author_is_required()
    {
        $this->post('/books', [
            'title' => 'Cool Book Title',
            'author' => '',
        ])->assertSessionHasErrors('author');
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
               'title' => 'Cool Book Title',
               'author' => 'Curder',
           ]);

        $book = Book::first();

        $response = $this->patch($book->path(), [
           'title' => 'New Title',
           'author' => 'New Author',
       ]);

        $response->assertRedirect($book->fresh()->path());

        $this->assertEquals('New Title', $book->fresh()->title);
        $this->assertEquals('New Author', $book->fresh()->author);
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Cool Book Title',
            'author' => 'Curder',
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $response->assertRedirect('/books');

        $this->assertCount(0, Book::all());
    }
}
